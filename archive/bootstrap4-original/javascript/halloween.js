(function() {
    'use strict'; 
// === DATUMSSTEUERUNG ===
function isInActiveDateRange() {
    const heute = new Date();
    const jahr = heute.getFullYear();

    // Start: 22.10. 00:00 Uhr
    const start = new Date(jahr, 9, 22, 0, 0, 0);  // Monat 9 = Oktober
    // Ende exklusiv: 01.11. 00:00 Uhr (damit 31.10. komplett dabei ist)
    const ende = new Date(jahr, 10, 1, 0, 0, 0);   // Monat 10 = November

    return heute >= start && heute < ende;
}

// === FR&uuml;HE DATUMSABFRAGE ===
if (!isInActiveDateRange()) {
    console.log('Halloween-Saison nicht aktiv - Geist wird nicht geladen');
    console.log('Aktuelles Datum:', new Date().toLocaleString('de-DE'));
    console.log('Aktiv vom 22.10. bis 31.10.'); 
    return; // BEENDE DAS SCRIPT HIER!
}

console.log('Halloween-Saison aktiv - Geist wird geladen');
console.log('Aktuelles Datum:', new Date().toLocaleString('de-DE'));
	
 /*
	 
// === DATUMSSTEUERUNG ===
    function isInActiveDateRange() {
        const heute = new Date();
        const jahr = heute.getFullYear();

        // KORRIGIERT: Start: 30.09. 00:00 Uhr (war vorher 26.10.)
        const start = new Date(jahr, 8, 30, 0, 0, 0);  // Monat 8 = September
        // Ende exklusiv: 01.11. 00:00 Uhr (damit 31.10. komplett dabei ist)
        const ende = new Date(jahr, 10, 1, 0, 0, 0);   // Monat 10 = November

        return heute >= start && heute < ende;
    }

    // === FR&uuml;HE DATUMSABFRAGE ===
    if (!isInActiveDateRange()) {
        console.log('Halloween-Saison nicht aktiv - Geist wird nicht geladen');
        console.log('Aktuelles Datum:', new Date().toLocaleString('de-DE'));
        console.log('Aktiv vom 30.09. bis 31.10.'); // KORRIGIERT: war vorher 26.10.
        return; // BEENDE DAS SCRIPT HIER!
    }

    console.log('Halloween-Saison aktiv - Geist wird geladen');
    console.log('Aktuelles Datum:', new Date().toLocaleString('de-DE'));
   	  */
    // Konfiguration
    const CONFIG = {
        INITIAL_DELAY: 3000, // 5 Sekunden erste Wartezeit
        GHOST_SPEED: 4.0, // Geschwindigkeit der Flucht
        FLEE_DISTANCE: 180, // Mindestabstand zur Maus (Desktop)
        DISAPPEAR_TIME_DESKTOP: 5000, // 10 Sekunden auf Desktop
        DISAPPEAR_TIME_MOBILE: 2000, // 3 Sekunden auf Mobile (l&auml;nger f&uuml;r bessere UX)
        RANDOM_APPEAR_MIN: 8000, // Minimum 8 Sekunden
        RANDOM_APPEAR_MAX: 25000, // Maximum 25 Sekunden
        MOBILE_FLEE_DISTANCE: 80, // Reduzierter Abstand f&uuml;r Mobile
        MOBILE_GHOST_SIZE: 60, // Gr&ouml;&szlig;ere Gr&ouml;&szlig;e f&uuml;r Mobile (war 50)
        DESKTOP_GHOST_SIZE: 100, // Gr&ouml;&szlig;ere Gr&ouml;&szlig;e f&uuml;r Desktop (war 80)
        MOBILE_CLICKS_MIN: 1, // Minimum 3 Klicks auf Mobile
        MOBILE_CLICKS_MAX: 3, // Maximum 9 Klicks auf Mobile
        MOBILE_MISS_PENALTY: 45000, // 45 Sekunden Strafe bei Verfehlen
        DESKTOP_CLICKS_MIN: 1, // Minimum 2 Klicks auf Desktop (neues Multi-Klick-System)
        DESKTOP_CLICKS_MAX: 2, // Maximum 4 Klicks auf Desktop (schwieriger als vorher)
        CLICK_POSITION_CHANGE_DELAY: 300, // Verzögerung beim Positionswechsel
        
        // Teaser-Effekt: Ghost schaut vom Rand herein während Wartezeit
        TEASER_ENABLED: true, // Teaser-Effekt aktivieren
        TEASER_INTERVAL_MIN: 1000, // Minimum 3 Sekunden zwischen Teasern
        TEASER_INTERVAL_MAX: 3000, // Maximum 8 Sekunden zwischen Teasern
        TEASER_DURATION: 800, // Wie lange der Teaser sichtbar ist (ms)
        TEASER_PEEK_DISTANCE: 40, // Wie weit der Ghost hereinschaut (px)
        
        // Erweiterte Wartezeit nach erfolgreichem Gutschein
        SUCCESS_COOLDOWN_MIN: 180000, // 3 Minuten = 180000ms (Desktop)
        SUCCESS_COOLDOWN_MAX: 480000, // 8 Minuten = 480000ms (Desktop)
        SUCCESS_COOLDOWN_MOBILE: 480000, // 8 Minuten = 480000ms (Mobile - fix)
        
        // Intelligentes HALLOWEEN25 Belohnungssystem
        MIN_INTERACTIONS_FOR_JACKPOT: 10, // Mindestens 8 Interaktionen vor HALLOWEEN25
        MIN_SUCCESS_COUNT_FOR_JACKPOT: 3, // Mindestens 3 erfolgreiche Gutscheine vor HALLOWEEN25
        JACKPOT_UNLOCK_DELAY: 1800000, // 30 Minuten Mindest-Spielzeit vor HALLOWEEN25 (1800000ms)
        
        // Neues Belohnungs-System
        MAX_RARE_COUPONS_PER_DAY: 5, // Maximal 7x HALLOWEEN25 pro Tag
        MISS_STREAK_FOR_BONUS: 3, // Nach 3 verpassten Versuchen Bonus
        BONUS_RARE_CHANCE: 0.8, // 80% Chance auf HALLOWEEN25 nach Miss-Streak
		
        MISS_STREAK_RESET_TIME: 3600000 // 1 Stunde bis Miss-Streak zur&uuml;ckgesetzt wird
    };
    
    // Erweiterte Gutschein-Codes mit FontAwesome 6 Icons
    const COUPONS = {
        // H&auml;ufigster Gutschein (75%)
        common: {
            code: 'JACK-O-LANTERN5',
            description: '5% Rabatt auf deinen Einkauf!',
            validity: 'Einl&ouml;sbar bis 31.10.2025 - Nicht auf Sonderangebote',
            icon: 'fa-solid fa-pumpkin',
            text: 'Ohh das war der kleine Gutschein! Versuche es erneut um einen h&ouml;heren Gutschein zu gewinnen.',
            chance: 0.75
        },
        
        // Free Seeds Gutscheine (15% gesamt)
        freeSeeds1: {
            code: 'SOLO1',
            description: '1x Free Seeds - Gratis Samen f&uuml;r dich!',
            validity: 'Im Warenkorb einl&ouml;sen um die Samen zu erhalten - Bei Sonderangebote im Kommentarfled eintragen',
            icon: 'fa-solid fa-seedling',
            text: 'Gl&uuml;ckwunsch! Du hast 1x Free Seeds gewonnen! L&ouml;se den Code im Warenkorb ein oder versuch dein Gl&uuml;ck noch einmal – auch die besten Grower geben nicht beim ersten Versuch auf!',
            chance: 0.05,
            type: 'freeSeeds'
        },
        
        freeSeeds2: {
            code: 'GHOST2',
            description: '2x Free Seeds - Doppelte Freude!',
            validity: 'Im Warenkorb einl&ouml;sen um die Samen zu erhalten - Bei Sonderangebote im Kommentarfled eintragen',
            icon: 'fa-solid fa-seedling',
            text: 'Fantastisch! Du hast 2x Free Seeds gewonnen! Code im Warenkorb einl&ouml;sen oder nochmal probieren – dein Gl&uuml;ck wartet!',
            chance: 0.05,
            type: 'freeSeeds'
        },
        
        freeSeeds3: {
            code: 'HANFI3',
            description: '3x Free Seeds - Triple Power!',
            validity: 'Im Warenkorb einl&ouml;sen um die Samen zu erhalten - Bei Sonderangebote im Kommentarfled eintragen',
            icon: 'fa-solid fa-seedling',
            text: 'Unglaublich! Du hast 3x Free Seeds gewonnen! L&ouml;se jetzt den Code im Warenkorb ein oder dreh noch &apos;ne Runde – dein Gl&uuml;ck ist zum Greifen nah',
            chance: 0.05,
            type: 'freeSeeds'
        },
        
        // Mittlere Gutscheine (10% gesamt)
        medium1: {
            code: 'BOOH10',
            description: '10% Rabatt auf deinen Einkauf!',
            validity: 'Einl&ouml;sbar bis 31.10.2025 - Nicht auf Sonderangebote',
            icon: 'fa-solid fa-ghost',
            text: 'Super! Du hast einen 10% Gutschein gewonnen! Ein sch&ouml;ner Rabatt f&uuml;r deinen Einkauf.',
            chance: 0.05
        },
        
        medium2: {
            code: 'ZISCH15',
            description: '15% Rabatt auf deinen Einkauf!',
            validity: 'Einl&ouml;sbar bis 31.10.2025 - Nicht auf Sonderangebote',
            icon: 'fa-solid fa-ghost',
            text: 'Gro&szlig;artig! Du hast einen 15% Gutschein gewonnen! Das ist ein toller Rabatt!',
            chance: 0.05
        },
        
        // Jackpot (2%) - NUR nach geduldigen Klicks verf&uuml;gbar!
        jackpot: {
            code: 'HALLOWEEN25',
            description: '25&euro; Rabatt ab 95&euro; Einkaufswert!',
            validity: 'Einl&ouml;sbar bis 31.10.2025 - Nicht auf Sonderangebote',
            icon: 'fa-solid fa-crown',
            text: '&#127881; JACKPOT! &#127881; Du hast den 25&euro; Gutschein gewonnen! Das ist der absolute Hauptgewinn! Deine Geduld hat sich ausgezahlt!',
            chance: 0.02,
            requiresPatience: true // Spezielle Markierung f&uuml;r Gedulds-Belohnung
        }
    };
    
    // Globale Variablen
    let ghost = null;
    let isVisible = false;
    let isInitialized = false;
    let fleeTimeout = null;
    let disappearTimeout = null;
    let nextAppearTimeout = null;
    let mouseX = 0;
    let mouseY = 0;
    let lastScrollY = 0;
    let hasScrolled = false;
    let isMobile = false;
    let modalOpen = false;
    let mobileClickCount = 0;
    let mobileClicksRequired = 0; // Zufällige Anzahl für aktuelle Session
    let mobileClickSession = false;
    let desktopClickCount = 0; // NEU: Klick-Counter für Desktop
    let desktopClicksRequired = 0; // NEU: Zufällige Anzahl für Desktop-Session
    let desktopClickSession = false; // NEU: Desktop Multi-Klick aktiv
    let positionChangeTimeout = null;
    let lastSuccessTime = 0; // Für 8-Minuten Cooldown
    let sessionStartTime = Date.now(); // Session-Start für Gedulds-Tracking
    let teaserInterval = null; // NEU: Interval für Teaser-Effekt
    let teaserTimeout = null; // NEU: Timeout für einzelnen Teaser
    let isMobileMiss = false; // NEU: Flag für Mobile-Verfehlen
    
    // Neue Variablen für Belohnungs-System
    let missStreak = 0;
    let lastMissTime = 0;
    let dailyStats = {
        date: '',
        rareCouponsGiven: 0,
        totalMisses: 0,
        totalCatches: 0,
        totalInteractions: 0, // Neue Statistik f&uuml;r Interaktionen
        successfulCoupons: 0, // Neue Statistik f&uuml;r erfolgreiche Gutscheine
        sessionStartTime: 0 // Session-Start Zeit
    };

    // LocalStorage und Cookie Schl&uuml;ssel
    const STORAGE_KEY = 'halloween_ghost_stats';
    const COOKIE_PREFIX = 'halloween_coupon_';
    
    // Mobile Erkennung
    function detectMobile() {
        return window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    // Cookie-Funktionen f&uuml;r einmalige Gutschein-Vergabe
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }
    
    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    
    function hasReceivedCoupon(couponCode) {
        return getCookie(COOKIE_PREFIX + couponCode) === 'received';
    }
    
    function markCouponAsReceived(couponCode) {
        setCookie(COOKIE_PREFIX + couponCode, 'received', 365); // 1 Jahr
        console.log('Gutschein als erhalten markiert:', couponCode);
    }
    
    // T&auml;gliche Statistiken laden
    function loadDailyStats() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                const data = JSON.parse(stored);
                const today = new Date().toDateString();
                
                if (data.date === today) {
                    dailyStats = data;
                    // Session-Start Zeit setzen falls nicht vorhanden
                    if (!dailyStats.sessionStartTime) {
                        dailyStats.sessionStartTime = Date.now();
                        sessionStartTime = dailyStats.sessionStartTime;
                    } else {
                        sessionStartTime = dailyStats.sessionStartTime;
                    }
                    console.log('T&auml;gliche Stats geladen:', dailyStats);
                } else {
                    // Neuer Tag - Stats zur&uuml;cksetzen
                    dailyStats = {
                        date: today,
                        rareCouponsGiven: 0,
                        totalMisses: 0,
                        totalCatches: 0,
                        totalInteractions: 0,
                        successfulCoupons: 0,
                        sessionStartTime: Date.now()
                    };
                    sessionStartTime = dailyStats.sessionStartTime;
                    saveDailyStats();
                    console.log('Neuer Tag - Stats zur&uuml;ckgesetzt');
                }
            } else {
                // Erste Nutzung
                dailyStats.date = new Date().toDateString();
                dailyStats.sessionStartTime = Date.now();
                sessionStartTime = dailyStats.sessionStartTime;
                saveDailyStats();
            }
        } catch (e) {
            console.warn('Fehler beim Laden der Stats:', e);
            dailyStats.date = new Date().toDateString();
            dailyStats.sessionStartTime = Date.now();
            sessionStartTime = dailyStats.sessionStartTime;
        }
    }
    
    // T&auml;gliche Statistiken speichern
    function saveDailyStats() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(dailyStats));
        } catch (e) {
            console.warn('Fehler beim Speichern der Stats:', e);
        }
    }
    
    // Miss-Streak pr&uuml;fen und aktualisieren
    function updateMissStreak() {
        const now = Date.now();
        
        // Pr&uuml;fen ob Miss-Streak abgelaufen ist (1 Stunde)
        if (now - lastMissTime > CONFIG.MISS_STREAK_RESET_TIME) {
            missStreak = 0;
        }
        
        missStreak++;
        lastMissTime = now;
        dailyStats.totalMisses++;
        dailyStats.totalInteractions++;
        saveDailyStats();
        
        console.log('Miss-Streak aktualisiert:', missStreak, 'Total Misses heute:', dailyStats.totalMisses);
    }
    
    // Miss-Streak zur&uuml;cksetzen (bei erfolgreichem Fang)
    function resetMissStreak() {
        missStreak = 0;
        dailyStats.totalCatches++;
        dailyStats.totalInteractions++;
        dailyStats.successfulCoupons++;
        saveDailyStats();
        
        console.log('Miss-Streak zur&uuml;ckgesetzt. Total Catches heute:', dailyStats.totalCatches);
    }
    
    // INTELLIGENTE GEDULDS-PR&Uuml;FUNG F&Uuml;R HALLOWEEN25
    function isPatientEnoughForJackpot() {
        const sessionDuration = Date.now() - sessionStartTime;
        const hasEnoughInteractions = dailyStats.totalInteractions >= CONFIG.MIN_INTERACTIONS_FOR_JACKPOT;
        const hasEnoughSuccesses = dailyStats.successfulCoupons >= CONFIG.MIN_SUCCESS_COUNT_FOR_JACKPOT;
        const hasPlayedLongEnough = sessionDuration >= CONFIG.JACKPOT_UNLOCK_DELAY;
        
        console.log('Gedulds-Pr&uuml;fung f&uuml;r HALLOWEEN25:');
        console.log('- Session-Dauer:', Math.round(sessionDuration / 60000), 'Minuten (ben&ouml;tigt:', Math.round(CONFIG.JACKPOT_UNLOCK_DELAY / 60000), ')');
        console.log('- Interaktionen:', dailyStats.totalInteractions, '(ben&ouml;tigt:', CONFIG.MIN_INTERACTIONS_FOR_JACKPOT, ')');
        console.log('- Erfolgreiche Gutscheine:', dailyStats.successfulCoupons, '(ben&ouml;tigt:', CONFIG.MIN_SUCCESS_COUNT_FOR_JACKPOT, ')');
        console.log('- Berechtigt f&uuml;r HALLOWEEN25:', hasEnoughInteractions && hasEnoughSuccesses && hasPlayedLongEnough);
        
        return hasEnoughInteractions && hasEnoughSuccesses && hasPlayedLongEnough;
    }
    
    // Intelligente Gutschein-Auswahl mit Gedulds-System
    function selectCoupon() {
        // Alle verf&uuml;gbaren Gutscheine sammeln
        const availableCoupons = [];
        
        // Pr&uuml;fe jeden Gutschein-Typ
        Object.values(COUPONS).forEach(coupon => {
            // HALLOWEEN25 nur verf&uuml;gbar wenn Geduld bewiesen wurde
            if (coupon.requiresPatience && !isPatientEnoughForJackpot()) {
                console.log('HALLOWEEN25 noch nicht verf&uuml;gbar - Geduld erforderlich!');
                return; // &Uuml;berspringe HALLOWEEN25
            }
            
            if (!hasReceivedCoupon(coupon.code)) {
                availableCoupons.push(coupon);
            }
        });
        
        // Wenn keine Gutscheine mehr verf&uuml;gbar sind, gib den h&auml;ufigsten zur&uuml;ck
        if (availableCoupons.length === 0) {
            console.log('Alle verf&uuml;gbaren Gutscheine bereits erhalten - verwende JACK-O-LANTERN5');
            return COUPONS.common;
        }
        
        // Pr&uuml;fen ob t&auml;gliches Limit f&uuml;r HALLOWEEN25 erreicht
        if (dailyStats.rareCouponsGiven >= CONFIG.MAX_RARE_COUPONS_PER_DAY) {
            // Entferne HALLOWEEN25 aus verf&uuml;gbaren Gutscheinen
            const filteredCoupons = availableCoupons.filter(c => c.code !== 'HALLOWEEN25');
            if (filteredCoupons.length > 0) {
                availableCoupons.splice(0, availableCoupons.length, ...filteredCoupons);
            }
        }
        
        // Gewichtete Zufallsauswahl basierend auf Wahrscheinlichkeiten
        const random = Math.random();
        let cumulativeChance = 0;
        
        // Sortiere nach Wahrscheinlichkeit (niedrigste zuerst f&uuml;r korrekte kumulative Berechnung)
        const sortedCoupons = availableCoupons.sort((a, b) => a.chance - b.chance);
        
        for (const coupon of sortedCoupons) {
            cumulativeChance += coupon.chance;
            if (random <= cumulativeChance) {
                // Spezielle Behandlung f&uuml;r HALLOWEEN25
                if (coupon.code === 'HALLOWEEN25') {
                    dailyStats.rareCouponsGiven++;
                    saveDailyStats();
                    console.log('&#127881; HALLOWEEN25 vergeben nach geduldigen Klicks! Total heute:', dailyStats.rareCouponsGiven, '/', CONFIG.MAX_RARE_COUPONS_PER_DAY);
                }
                
                console.log('Gutschein ausgew&auml;hlt:', coupon.code, 'Chance:', coupon.chance, 'Random:', random);
                return coupon;
            }
        }
        
        // Fallback: Gib den h&auml;ufigsten verf&uuml;gbaren Gutschein zur&uuml;ck
        const commonCoupon = availableCoupons.find(c => c.code === 'JACK-O-LANTERN5') || availableCoupons[0];
        console.log('Fallback Gutschein:', commonCoupon.code);
        return commonCoupon;
    }
    
    // Fortschrittsanzeige erstellen
    function createProgressIndicator() {
        if (document.getElementById('halloween-progress')) return;
        
        const progressHTML = `
            <div id="halloween-progress" class="halloween-progress" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9998; background: rgba(0, 0, 0, 0.8); color: white; padding: 10px 15px; border-radius: 10px; border: 2px solid #00ff88; font-family: Arial, sans-serif;">
                <div class="halloween-progress-text" id="halloween-progress-text" style="font-size: 16px; font-weight: bold; margin-bottom: 5px; text-align: center;">3x</div>
                <div class="halloween-progress-bar" style="width: 100px; height: 8px; background: rgba(255, 255, 255, 0.2); border-radius: 4px; overflow: hidden;">
                    <div class="halloween-progress-fill" id="halloween-progress-fill" style="height: 100%; background: linear-gradient(90deg, #00ff88, #00cc6a); width: 0%; transition: width 0.3s ease;"></div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', progressHTML);
    }
    
    // Fortschrittsanzeige aktualisieren - DEAKTIVIERT F&uuml;R MOBILE
    function updateProgressIndicator() {
        // Klick-Anzeige auf Mobilgeräten deaktiviert
        return;
    }
    
    // Fortschrittsanzeige verstecken
    function hideProgressIndicator() {
        const progressContainer = document.getElementById('halloween-progress');
        if (progressContainer) {
            progressContainer.style.display = 'none';
        }
        mobileClickCount = 0;
        mobileClicksRequired = 0;
        mobileClickSession = false;
    }
    
    // Position f&uuml;r Mobile-Klicks wechseln
    function moveGhostToNewPosition() {
        if (!isMobile || !isVisible) return;
        
        // Kurze Verz&ouml;gerung f&uuml;r bessere UX
        if (ghost) {
            ghost.style.opacity = '0.5';
        }
        
        if (positionChangeTimeout) {
            clearTimeout(positionChangeTimeout);
        }
        
        positionChangeTimeout = setTimeout(() => {
            const newPosition = getRandomPosition();
            if (ghost) {
                ghost.style.left = newPosition.x + 'px';
                ghost.style.top = newPosition.y + 'px';
                ghost.style.opacity = '0.9';
            }
            console.log('Ghost Position ge&auml;ndert zu:', newPosition.x, newPosition.y);
        }, CONFIG.CLICK_POSITION_CHANGE_DELAY);
    }
    
    // Modal HTML erstellen mit FontAwesome 6 Icons
    function createModal() {
        if (document.getElementById('halloween-modal')) return;
        
        const modalHTML = `
            <div id="halloween-modal" class="halloween-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease-in-out;">
                <div class="halloween-modal-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(5px);"></div>
                <div class="halloween-modal-content" style="position: relative; background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460); border: 3px solid #00ff88; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 255, 136, 0.3); max-width: 500px; width: 90%; max-height: 90vh; overflow: hidden; transform: scale(0.7); transition: transform 0.3s ease-out;">
                    <div class="halloween-modal-header" style="background: linear-gradient(135deg, #2d1b69, #11998e); padding: 20px; text-align: center; position: relative; border-bottom: 2px solid #00ff88;">
                        <h2 id="halloween-modal-title" style="margin: 0; color: white; font-size: 28px; font-weight: bold; text-shadow: 0 0 10px rgba(0, 255, 136, 0.5); font-family: Arial, sans-serif;">&#129415; BOOOH!</h2>
                        <button class="halloween-modal-close" id="halloween-modal-close" style="position: absolute; top: 15px; right: 20px; background: none; border: none; color: white; font-size: 30px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">&times;</button>
                    </div>
                    <div class="halloween-modal-body" style="padding: 30px; text-align: center;">
                        <div class="halloween-coupon-display" style="color: white;">
                            <div class="halloween-coupon-icon" id="halloween-coupon-icon" style="font-size: 60px; margin-bottom: 20px; color: #00ff88;">
                                <i class="fa-solid fa-pumpkin"></i>
                            </div>
                            <div class="halloween-coupon-code" id="halloween-coupon-code" style="font-size: 36px; font-weight: bold; color: #00ff88; margin: 20px 0; padding: 15px; background: rgba(0, 255, 136, 0.1); border: 2px dashed #00ff88; border-radius: 10px; text-shadow: 0 0 10px rgba(0, 255, 136, 0.5); letter-spacing: 3px; font-family: 'Courier New', monospace;">JACK-O-LANTERN5</div>
                            <div class="halloween-coupon-description" id="halloween-coupon-description" style="font-size: 18px; margin: 20px 0; color: #cccccc; line-height: 1.4;">5% Rabatt auf deinen Einkauf!</div>
                            <div class="halloween-coupon-validity" id="halloween-coupon-validity" style="font-size: 14px; color: #999; margin: 10px 0;">Einl&ouml;sbar bis 31.10.2025 - Nicht auf Sonderangebote</div>
                            <div class="halloween-coupon-text" id="halloween-coupon-text" style="font-size: 16px; margin: 20px 0; color: #ffcc00; font-weight: bold; line-height: 1.4; padding: 10px; background: rgba(255, 204, 0, 0.1); border-radius: 8px;">Ohh das war der kleine Gutschein! Versuche es erneut um einen h&ouml;heren Gutschein zu gewinnen.</div>
                            <button class="halloween-copy-button" id="halloween-copy-button" style="background: linear-gradient(135deg, #00ff88, #00cc6a); color: white; border: none; padding: 15px 30px; font-size: 16px; font-weight: bold; border-radius: 25px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3); margin: 20px 0;">
                              Code kopieren
                            </button>
                            <div class="halloween-copy-success" id="halloween-copy-success" style="display: none; color: #00ff88; font-size: 16px; font-weight: bold; margin: 20px 0;">
                               Code wurde kopiert!
                            </div>
                        </div>
                        <div class="halloween-stats" id="halloween-stats"></div>
                    </div>
                    <div class="halloween-modal-footer" style="background: rgba(0, 0, 0, 0.3); padding: 15px 20px; text-align: center; border-top: 1px solid rgba(0, 255, 136, 0.3);">
                        <p style="margin: 0; color: #999; font-size: 14px;">Verwende den Code bei deinem n&auml;chsten Einkauf auf mr-hanf.de - <a href="https://mr-hanf.de/blog/aktuelle-newsletter/gespenstisches-schwebt-ueber-mr-hanf" style="color: #00ff88; text-decoration: none;" target="_blank">Spielinformationen</a></p>

                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Event Listeners f&uuml;r Modal
        const modal = document.getElementById('halloween-modal');
        const closeBtn = document.getElementById('halloween-modal-close');
        const overlay = modal.querySelector('.halloween-modal-overlay');
        const copyBtn = document.getElementById('halloween-copy-button');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeModal);
        }
        
        if (copyBtn) {
            copyBtn.addEventListener('click', copyToClipboard);
        }
        
        // ESC-Taste zum Schlie&szlig;en
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalOpen) {
                closeModal();
            }
        });
        
        console.log('Modal erstellt und Event Listeners hinzugef&uuml;gt');
    }
    
    // Modal &ouml;ffnen mit Gutschein-Daten
    function openModal(coupon) {
        if (modalOpen) {
            console.log('Modal bereits ge&ouml;ffnet');
            return;
        }
        
        console.log('&Ouml;ffne Modal f&uuml;r Gutschein:', coupon.code);
        
        createModal();
        
        const modal = document.getElementById('halloween-modal');
        const modalContent = modal.querySelector('.halloween-modal-content');
        const iconElement = document.getElementById('halloween-coupon-icon');
        const codeElement = document.getElementById('halloween-coupon-code');
        const descriptionElement = document.getElementById('halloween-coupon-description');
        const validityElement = document.getElementById('halloween-coupon-validity');
        const textElement = document.getElementById('halloween-coupon-text');
        
        if (!modal || !iconElement || !codeElement || !descriptionElement || !validityElement || !textElement) {
            console.error('Modal Elemente nicht gefunden!');
            return;
        }
        
        // Gutschein-Daten setzen mit FontAwesome 6 Icons
        iconElement.innerHTML = '<i class="' + coupon.icon + '"></i>';
        codeElement.textContent = coupon.code;
        descriptionElement.innerHTML = coupon.description;
        validityElement.innerHTML = coupon.validity;
        textElement.innerHTML = coupon.text;
        
        // Spezielle Styling f&uuml;r Free Seeds
        if (coupon.type === 'freeSeeds') {
            codeElement.style.background = 'rgba(0, 255, 136, 0.2)';
            codeElement.style.borderColor = '#00ff88';
            codeElement.style.fontSize = '32px';
            codeElement.style.fontWeight = 'bold';
            textElement.style.background = 'rgba(0, 255, 136, 0.1)';
            textElement.style.color = '#00ff88';
        }
        
        // Spezielle Styling f&uuml;r Jackpot - EXTRA SPEKTAKUL&Auml;R!
        if (coupon.code === 'HALLOWEEN25') {
            // Goldenes Design f&uuml;r den Jackpot
            codeElement.style.background = 'linear-gradient(135deg, #ffd700, #ffed4e, #ffd700)';
            codeElement.style.color = '#000';
            codeElement.style.borderColor = '#ffd700';
            codeElement.style.fontSize = '42px';
            codeElement.style.fontWeight = 'bold';
            codeElement.style.textShadow = '0 0 20px rgba(255, 215, 0, 0.8)';
            codeElement.style.animation = 'pulse 1.5s infinite';
            
            textElement.style.background = 'rgba(255, 215, 0, 0.2)';
            textElement.style.color = '#ffd700';
            textElement.style.fontSize = '18px';
            textElement.style.fontWeight = 'bold';
            textElement.style.textShadow = '0 0 10px rgba(255, 215, 0, 0.5)';
            
            iconElement.style.color = '#ffd700';
            iconElement.style.fontSize = '80px';
            iconElement.style.textShadow = '0 0 20px rgba(255, 215, 0, 0.8)';
            
            // Titel f&uuml;r Jackpot &auml;ndern
            const titleElement = document.getElementById('halloween-modal-title');
            if (titleElement) {
                titleElement.innerHTML = '&#128081; JACKPOT! &#128081;';
                titleElement.style.color = '#ffd700';
                titleElement.style.textShadow = '0 0 20px rgba(255, 215, 0, 0.8)';
            }
            
            // Pulse Animation hinzuf&uuml;gen
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Modal anzeigen
        modal.style.display = 'flex';
        modalOpen = true;
        document.body.style.overflow = 'hidden';
        
        // Animationen
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
        
        // Gutschein als erhalten markieren
        markCouponAsReceived(coupon.code);
        
        // Erfolgszeit f&uuml;r Cooldown setzen
        lastSuccessTime = Date.now();
        
        console.log('Modal erfolgreich ge&ouml;ffnet f&uuml;r:', coupon.code);
    }
    
    // Modal schlie&szlig;en
    function closeModal() {
        const modal = document.getElementById('halloween-modal');
        const modalContent = modal ? modal.querySelector('.halloween-modal-content') : null;
        
        if (!modal || !modalOpen) {
            console.log('Modal nicht ge&ouml;ffnet oder nicht gefunden');
            return;
        }
        
        try {
            modal.style.opacity = '0';
            if (modalContent) {
                modalContent.style.transform = 'scale(0.7)';
            }
            
            setTimeout(() => {
                modal.style.display = 'none';
                modalOpen = false;
                document.body.style.overflow = '';
                
                // Nächstes Erscheinen mit Cooldown planen
                // Desktop: Zufällige Zeit zwischen 3-8 Minuten, Mobile: Fix 8 Minuten
                const cooldownTime = isMobile 
                    ? CONFIG.SUCCESS_COOLDOWN_MOBILE 
                    : Math.floor(Math.random() * (CONFIG.SUCCESS_COOLDOWN_MAX - CONFIG.SUCCESS_COOLDOWN_MIN + 1)) + CONFIG.SUCCESS_COOLDOWN_MIN;
                
                scheduleNextAppearance(cooldownTime);
                
                const cooldownMinutes = Math.round(cooldownTime / 60000);
                console.log('Modal erfolgreich geschlossen - nächstes Erscheinen in', cooldownMinutes, 'Minuten');
            }, 300);
            
        } catch (error) {
            console.error('Fehler beim Schlie&szlig;en des Modals:', error);
            modalOpen = false;
            document.body.style.overflow = '';
        }
    }
    
    // Code in Zwischenablage kopieren
    function copyToClipboard() {
        try {
            const codeElement = document.getElementById('halloween-coupon-code');
            const copyButton = document.getElementById('halloween-copy-button');
            const copySuccess = document.getElementById('halloween-copy-success');
            
            if (!codeElement) {
                console.error('Code Element nicht gefunden!');
                return;
            }
            
            const code = codeElement.textContent;
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(() => {
                    if (copyButton) copyButton.style.display = 'none';
                    if (copySuccess) copySuccess.style.display = 'block';
                    
                    setTimeout(() => {
                        if (copyButton) copyButton.style.display = 'block';
                        if (copySuccess) copySuccess.style.display = 'none';
                    }, 3000);
                }).catch(() => {
                    fallbackCopyToClipboard(code, copyButton, copySuccess);
                });
            } else {
                fallbackCopyToClipboard(code, copyButton, copySuccess);
            }
        } catch (error) {
            console.error('Fehler beim Kopieren:', error);
        }
    }
    
    // Fallback f&uuml;r Kopieren
    function fallbackCopyToClipboard(text, copyButton, copySuccess) {
        try {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            
            if (successful) {
                if (copyButton) copyButton.style.display = 'none';
                if (copySuccess) copySuccess.style.display = 'block';
                
                setTimeout(() => {
                    if (copyButton) copyButton.style.display = 'block';
                    if (copySuccess) copySuccess.style.display = 'none';
                }, 3000);
            }
        } catch (err) {
            console.warn('Kopieren fehlgeschlagen:', err);
        }
    }
    
    // Verbesserte Positionierung - Verhindert Abschneiden (Mobile-optimiert)
    function getRandomPosition() {
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const scrollY = window.scrollY;
        
        // Größe basierend auf Gerät
        const ghostSize = isMobile ? CONFIG.MOBILE_GHOST_SIZE : CONFIG.DESKTOP_GHOST_SIZE;
        
        // WICHTIG: Größerer Margin für Mobile, damit Geist nicht abgeschnitten wird
        const margin = isMobile ? 50 : 30;
        
        // WICHTIG: Zusätzlicher Abstand vom unteren Rand für Mobile (z.B. für Navigation)
        const bottomMargin = isMobile ? 100 : margin;
        
        // Sichtbaren Bereich berechnen mit Sicherheitsabstand
        const minX = margin;
        const maxX = viewportWidth - ghostSize - margin;
        const minY = scrollY + margin;
        const maxY = scrollY + viewportHeight - ghostSize - bottomMargin;
        
        // Sicherstellen, dass die Bereiche gültig sind (maxX/maxY müssen größer als minX/minY sein)
        const safeMaxX = Math.max(minX, maxX);
        const safeMaxY = Math.max(minY, maxY);
        
        // Position berechnen - nur wenn gültiger Bereich vorhanden
        let x, y;
        if (safeMaxX > minX) {
            x = Math.random() * (safeMaxX - minX) + minX;
        } else {
            x = minX; // Fallback: Mindestposition
        }
        
        if (safeMaxY > minY) {
            y = Math.random() * (safeMaxY - minY) + minY;
        } else {
            y = minY; // Fallback: Mindestposition
        }
        
        // Final-Check: Position muss innerhalb der Viewport-Grenzen sein
        x = Math.max(margin, Math.min(viewportWidth - ghostSize - margin, x));
        y = Math.max(scrollY + margin, Math.min(scrollY + viewportHeight - ghostSize - bottomMargin, y));
        
        console.log('Position generiert:', {
            x: Math.round(x), 
            y: Math.round(y), 
            ghostSize, 
            viewport: {width: viewportWidth, height: viewportHeight},
            margins: {top: margin, bottom: bottomMargin, sides: margin},
            isMobile
        });
        
        return { x, y };
    }
    
    // Verbesserte Element-Initialisierung
    function createGhost() {
        // Versuche verschiedene m&ouml;gliche IDs zu finden
        ghost = document.getElementById('halloween-ghost') || 
                document.getElementById('halloween-ghost aktion') ||
                document.querySelector('[id*="halloween-ghost"]');
        
        if (!ghost) {
            console.error('Halloween Ghost Element nicht gefunden! Erwartete ID: "halloween-ghost"');
            console.log('Verf&uuml;gbare Elemente mit "halloween" im Namen:');
            const halloweenElements = document.querySelectorAll('[id*="halloween"], [class*="halloween"]');
            halloweenElements.forEach(el => console.log('- Element:', el.tagName, 'ID:', el.id, 'Class:', el.className));
            return;
        }
        
        console.log('Halloween Ghost Element gefunden:', ghost.id || ghost.className);
        
        // CSS-Klasse hinzuf&uuml;gen falls nicht vorhanden
        if (!ghost.classList.contains('halloween-ghost')) {
            ghost.classList.add('halloween-ghost');
        }
        
        // Kritische Style-Korrekturen
        ghost.style.position = 'fixed';
        ghost.style.zIndex = '9999';
        ghost.style.cursor = 'pointer';
        ghost.style.pointerEvents = 'auto';
        
        // Korrekte Gr&ouml;&szlig;ensetzung
        const ghostSize = isMobile ? CONFIG.MOBILE_GHOST_SIZE : CONFIG.DESKTOP_GHOST_SIZE;
        ghost.style.width = ghostSize + 'px';
        ghost.style.height = ghostSize + 'px';
        
        // Bild-Styling korrigieren
        const img = ghost.querySelector('img');
        if (img) {
            console.log('Halloween Ghost Bild gefunden:', img.src);
            // Stelle sicher, dass das Bild die volle Gr&ouml;&szlig;e des Containers einnimmt
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'contain'; // Verhindert Verzerrung
            img.style.display = 'block';
        } else {
            console.warn('Kein <img> Element im Halloween Ghost gefunden');
        }
        
        console.log('Ghost Gr&ouml;&szlig;e gesetzt:', ghostSize + 'px', 'Mobile:', isMobile);
    }
    
    // Initialisierung
    function init() {
        if (isInitialized) return;
        
        console.log('Halloween Ghost wird initialisiert...');
        
        // Mobile-Erkennung
        isMobile = detectMobile();
        
        // Statistiken laden
        loadDailyStats();
        
        // Vorhandenes Ghost-Element verwenden
        createGhost();
        
        if (!ghost) {
            console.error('Halloween Ghost konnte nicht initialisiert werden - Element nicht gefunden');
            return;
        }
        
        // Fortschrittsanzeige f&uuml;r Mobile
        if (isMobile) {
            createProgressIndicator();
        }
        
        // Event Listeners
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('scroll', handleScroll);
        ghost.addEventListener('click', handleGhostClick);
        
        // Touch-Events f&uuml;r Mobile
        if (isMobile) {
            document.addEventListener('touchmove', handleTouchMove);
            document.addEventListener('touchstart', handleTouchStart);
        }
        
        // Initiale Verz&ouml;gerung
        setTimeout(() => {
            startContinuousAppearing();
        }, CONFIG.INITIAL_DELAY);
        
        isInitialized = true;
        
        console.log('Halloween Ghost initialisiert (Mobile:', isMobile, ')');
        console.log('Heutige Stats:', dailyStats);
        console.log('Session-Start:', new Date(sessionStartTime).toLocaleString('de-DE'));
    }
    
    // Touch-Events f&uuml;r Mobile
    function handleTouchMove(e) {
        if (e.touches.length > 0) {
            mouseX = e.touches[0].clientX;
            mouseY = e.touches[0].clientY;
            
            if (isVisible) {
                fleeFromMouse();
            }
        }
    }
    
    function handleTouchStart(e) {
        if (e.touches.length > 0) {
            mouseX = e.touches[0].clientX;
            mouseY = e.touches[0].clientY;
        }
    }
    
    // Maus-Bewegung verfolgen
    function handleMouseMove(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        if (isVisible && !isMobile) { // Nur auf Desktop fliehen
            fleeFromMouse();
        }
    }
    
    // Scroll-Erkennung
    function handleScroll() {
        const currentScrollY = window.scrollY;
        if (Math.abs(currentScrollY - lastScrollY) > 30) {
            hasScrolled = true;
            lastScrollY = currentScrollY;
        }
    }
    
    // Kontinuierliches Erscheinen starten
    function startContinuousAppearing() {
        showGhost();
        scheduleNextAppearance();
    }
    
    // Geist anzeigen mit verbesserter Positionierung
    function showGhost() {
        if (isVisible || modalOpen || !ghost) {
            console.log('showGhost &uuml;bersprungen - Visible:', isVisible, 'Modal:', modalOpen, 'Ghost:', !!ghost);
            return;
        }
        
        // Prüfe Cooldown nach erfolgreichem Gutschein
        const timeSinceSuccess = Date.now() - lastSuccessTime;
        const requiredCooldown = isMobile ? CONFIG.SUCCESS_COOLDOWN_MOBILE : CONFIG.SUCCESS_COOLDOWN_MAX;
        if (timeSinceSuccess < requiredCooldown) {
            const remainingTime = requiredCooldown - timeSinceSuccess;
            console.log('Cooldown aktiv - verbleibt:', Math.round(remainingTime / 1000), 'Sekunden');
            scheduleNextAppearance(remainingTime);
            return;
        }
        
        console.log('showGhost startet - Mobile:', isMobile);
        
        const position = getRandomPosition();
        
        ghost.style.left = position.x + 'px';
        ghost.style.top = position.y + 'px';
        ghost.style.display = 'block'; // Sichtbar machen
        ghost.classList.add('visible');
        isVisible = true;
        
        console.log('Geist positioniert bei:', position.x, position.y, 'Display:', ghost.style.display, 'Visible class:', ghost.classList.contains('visible'));
        
        // Mobile: Klick-Session starten (NACH dem Geist sichtbar ist)
        if (isMobile) {
            mobileClickCount = 0;
            // Zuf&auml;llige Anzahl von Klicks zwischen Min und Max
            mobileClicksRequired = Math.floor(Math.random() * (CONFIG.MOBILE_CLICKS_MAX - CONFIG.MOBILE_CLICKS_MIN + 1)) + CONFIG.MOBILE_CLICKS_MIN;
            mobileClickSession = true;
            
            // Kurze Verz&ouml;gerung damit Geist zuerst sichtbar wird
            setTimeout(() => {
                updateProgressIndicator();
            }, 500);
            
            console.log('Mobile Session gestartet - Klicks benötigt:', mobileClicksRequired);
        } else {
            // NEU: Desktop Multi-Klick-System
            desktopClickCount = 0;
            desktopClicksRequired = Math.floor(Math.random() * (CONFIG.DESKTOP_CLICKS_MAX - CONFIG.DESKTOP_CLICKS_MIN + 1)) + CONFIG.DESKTOP_CLICKS_MIN;
            desktopClickSession = true;
            
            console.log('Desktop Multi-Klick Session gestartet - Klicks benötigt:', desktopClicksRequired);
        }

        
        // Auto-Verschwinden nach Zeit
        const disappearTime = isMobile ? CONFIG.DISAPPEAR_TIME_MOBILE : CONFIG.DISAPPEAR_TIME_DESKTOP;
        disappearTimeout = setTimeout(() => {
            if (!modalOpen) {
                updateMissStreak();
                if (isMobile) {
                    isMobileMiss = true; // NEU: Mobile-Miss Flag setzen
                }
                hideGhost();
                console.log('Geist verpasst, Miss-Streak:', missStreak);
            }
        }, disappearTime);
        
        console.log('Geist erschienen f&uuml;r', disappearTime, 'ms (Mobile:', isMobile, ')');
    }
    
    // Geist verstecken mit display-Steuerung und Mobile-Miss-Logik
    function hideGhost() {
        if (!isVisible || !ghost) return;
        
        ghost.style.display = 'none'; // Verstecken
        ghost.classList.remove('visible', 'fleeing', 'teasing'); // NEU: auch teasing entfernen
        ghost.style.transition = ''; // NEU: Transition zurücksetzen
        isVisible = false;
        
        // Alle relevanten Timeouts löschen
        if (fleeTimeout) {
            clearTimeout(fleeTimeout);
            fleeTimeout = null;
        }
        if (disappearTimeout) {
            clearTimeout(disappearTimeout);
            disappearTimeout = null;
        }
        if (positionChangeTimeout) {
            clearTimeout(positionChangeTimeout);
            positionChangeTimeout = null;
        }
        
        // Mobile: Fortschrittsanzeige verstecken
        if (isMobile) {
            hideProgressIndicator();
            mobileClickSession = false; // Session beenden
        }
        
        // NEU: Desktop Multi-Klick-Session beenden
        if (!isMobile) {
            desktopClickSession = false;
        }
        
        // NEU: Mobile-Miss-Penalty anwenden
        if (isMobileMiss && isMobile) {
            console.log('Mobile-Miss erkannt - 45 Sekunden Strafe wird verhängt.');
            scheduleNextAppearance(CONFIG.MOBILE_MISS_PENALTY);
            isMobileMiss = false; // Flag für den nächsten Durchgang zurücksetzen
        }
        
        console.log('Geist wurde versteckt.');
    }
    
    // Verbesserte Flucht-Logik
    function fleeFromMouse() {
        if (!isVisible || isMobile || !ghost) return;
        
        const ghostRect = ghost.getBoundingClientRect();
        const ghostCenterX = ghostRect.left + ghostRect.width / 2;
        const ghostCenterY = ghostRect.top + ghostRect.height / 2;
        
        const distanceX = ghostCenterX - mouseX;
        const distanceY = ghostCenterY - mouseY;
        const distance = Math.sqrt(distanceX * distanceX + distanceY * distanceY);
        
        if (distance < CONFIG.FLEE_DISTANCE) {
            // Flucht-Animation aktivieren
            ghost.classList.add('fleeing');
            
            // Fluchtrichtung berechnen
            const angle = Math.atan2(distanceY, distanceX);
            const fleeX = ghostCenterX + Math.cos(angle) * CONFIG.GHOST_SPEED * 15;
            const fleeY = ghostCenterY + Math.sin(angle) * CONFIG.GHOST_SPEED * 15;
            
            // Verbesserte Grenzen-Pr&uuml;fung
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            const ghostSize = CONFIG.DESKTOP_GHOST_SIZE;
            const margin = 20;
            
            const newX = Math.max(margin, Math.min(viewportWidth - ghostSize - margin, fleeX - ghostSize/2));
            const newY = Math.max(margin, Math.min(viewportHeight - ghostSize - margin, fleeY - ghostSize/2));
            
            ghost.style.left = newX + 'px';
            ghost.style.top = newY + 'px';
            
            // Flucht-Animation nach kurzer Zeit entfernen
            if (fleeTimeout) clearTimeout(fleeTimeout);
            fleeTimeout = setTimeout(() => {
                ghost.classList.remove('fleeing');
            }, 300);
        }
    }
    
    // Geist-Klick behandeln - Mobile Multi-Klick, Desktop sofort
    function handleGhostClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Ghost geklickt! Mobile:', isMobile, 'Visible:', isVisible, 'Modal open:', modalOpen);
        
        if (!isVisible || modalOpen) {
            console.log('Klick ignoriert - Geist nicht sichtbar oder Modal bereits offen');
            return;
        }
        
        if (isMobile) {
            // Mobile: Multi-Klick-System (3-6 Klicks)
            mobileClickCount++;
            updateProgressIndicator();
            
            console.log('Mobile Klick:', mobileClickCount, 'von', mobileClicksRequired);
            
            if (mobileClickCount < mobileClicksRequired) {
                // Noch nicht genug Klicks - Position wechseln
                moveGhostToNewPosition();
                
                // Timer zur&uuml;cksetzen f&uuml;r l&auml;ngere Session
                if (disappearTimeout) {
                    clearTimeout(disappearTimeout);
                    disappearTimeout = setTimeout(() => {
                        if (!modalOpen) {
                            updateMissStreak();
                            isMobileMiss = true; // NEU: Mobile-Miss Flag setzen
                            hideGhost();
                            console.log('Mobile: Geist verpasst - Miss-Streak:', missStreak);
                        }
                    }, CONFIG.DISAPPEAR_TIME_MOBILE * 2); // Doppelte Zeit für Multi-Klick
                }
            } else {
                // Erforderliche Klicks erreicht - Gutschein zeigen
                const coupon = selectCoupon();
                resetMissStreak();
                
                hideGhost();
                
                // Kurze Verz&ouml;gerung vor Modal-&Ouml;ffnung
                setTimeout(() => {
                    openModal(coupon);
                }, 200);
                
                console.log('Mobile:', mobileClicksRequired, 'Klicks erreicht! Gutschein:', coupon.code);
            }
        } else {
            // NEU: Desktop Multi-Klick-System (2-4 Klicks)
            desktopClickCount++;
            
            console.log('Desktop Klick:', desktopClickCount, 'von', desktopClicksRequired);
            
            if (desktopClickCount < desktopClicksRequired) {
                // Noch nicht genug Klicks - Position wechseln
                moveGhostToNewPosition();
                
                // Timer zurücksetzen für längere Session
                if (disappearTimeout) {
                    clearTimeout(disappearTimeout);
                    disappearTimeout = setTimeout(() => {
                        if (!modalOpen) {
                            updateMissStreak();
                            hideGhost();
                            console.log('Desktop: Geist verpasst - Miss-Streak:', missStreak);
                        }
                    }, CONFIG.DISAPPEAR_TIME_DESKTOP * 1.5); // 1.5x Zeit für Multi-Klick
                }
            } else {
                // Erforderliche Klicks erreicht - Gutschein zeigen
                const coupon = selectCoupon();
                resetMissStreak();
                
                hideGhost();
                
                // Kurze Verzögerung vor Modal-Öffnung für bessere UX
                setTimeout(() => {
                    openModal(coupon);
                }, 100);
                
                console.log('Desktop:', desktopClicksRequired, 'Klicks erreicht! Gutschein:', coupon.code);
            }
        }
    }
    
    // NEU: Teaser-Effekt - Ghost schaut vom rechten Rand herein
    function showTeaser() {
        if (!ghost || isVisible || modalOpen) return;
        
        const viewportHeight = window.innerHeight;
        const ghostSize = isMobile ? CONFIG.MOBILE_GHOST_SIZE : CONFIG.DESKTOP_GHOST_SIZE;
        
        // Zufällige Y-Position (nicht zu nah am oberen/unteren Rand)
        const margin = 50;
        const randomY = margin + Math.random() * (viewportHeight - ghostSize - margin * 2);
        
        // Position am rechten Rand (fast außerhalb)
        const startX = window.innerWidth; // Komplett außerhalb
        const peekX = window.innerWidth - ghostSize - CONFIG.TEASER_PEEK_DISTANCE; // Hereinschauen (Ghost-Größe berücksichtigen)
        
        // Ghost positionieren (außerhalb)
        ghost.style.left = startX + 'px';
        ghost.style.top = randomY + 'px';
        ghost.style.display = 'block';
        ghost.classList.add('teasing'); // Spezielle CSS-Klasse für Teaser
        
        // Hereinsliden
        setTimeout(() => {
            ghost.style.transition = 'left 0.3s ease-out';
            ghost.style.left = peekX + 'px';
        }, 50);
        
        // Nach kurzer Zeit wieder verschwinden
        teaserTimeout = setTimeout(() => {
            ghost.style.transition = 'left 0.3s ease-in';
            ghost.style.left = startX + 'px';
            
            // Nach Animation verstecken
            setTimeout(() => {
                ghost.style.display = 'none';
                ghost.style.transition = ''; // Transition zurücksetzen
                ghost.classList.remove('teasing');
            }, 300);
        }, CONFIG.TEASER_DURATION);
        
        console.log('Teaser: Ghost schaut vom rechten Rand herein bei Y:', Math.round(randomY));
    }
    
    // NEU: Teaser-Intervall starten
    function startTeasers() {
        stopTeasers(); // Alte Teaser stoppen
        
        function scheduleNextTeaser() {
            const interval = CONFIG.TEASER_INTERVAL_MIN + 
                Math.random() * (CONFIG.TEASER_INTERVAL_MAX - CONFIG.TEASER_INTERVAL_MIN);
            
            teaserInterval = setTimeout(() => {
                showTeaser();
                scheduleNextTeaser(); // Nächsten Teaser planen
            }, interval);
        }
        
        scheduleNextTeaser();
        console.log('Teaser-System gestartet');
    }
    
    // NEU: Teaser-System stoppen
    function stopTeasers() {
        if (teaserInterval) {
            clearTimeout(teaserInterval);
            teaserInterval = null;
        }
        if (teaserTimeout) {
            clearTimeout(teaserTimeout);
            teaserTimeout = null;
        }
        
        // Ghost verstecken falls gerade Teaser aktiv
        if (ghost && ghost.classList.contains('teasing')) {
            ghost.style.display = 'none';
            ghost.style.transition = '';
            ghost.classList.remove('teasing');
        }
    }
    
    // Nächstes Erscheinen planen
    function scheduleNextAppearance(customDelay = null) {
        if (nextAppearTimeout) {
            clearTimeout(nextAppearTimeout);
        }
        
        const delay = customDelay || (CONFIG.RANDOM_APPEAR_MIN + Math.random() * (CONFIG.RANDOM_APPEAR_MAX - CONFIG.RANDOM_APPEAR_MIN));
        
        nextAppearTimeout = setTimeout(() => {
            stopTeasers(); // Teaser stoppen bevor Ghost erscheint
            showGhost();
            scheduleNextAppearance();
        }, delay);
        
        // NEU: Teaser-Effekt starten während Wartezeit
        if (CONFIG.TEASER_ENABLED && delay > 5000) { // Nur bei längeren Wartezeiten
            startTeasers();
        }
        
        console.log('Nächstes Erscheinen geplant in:', Math.round(delay / 1000), 'Sekunden');
    }
    
    // DOM Ready Event
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM bereits geladen
        setTimeout(init, 100);
    }
    
})();