/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - Reklamationsformular JavaScript
   Datei: includes/javascript/reklamation.js
   
   Interaktive Funktionen für das Reklamationsformular
   ----------------------------------------------------------------------------------------- */

// Globale Variablen
let selectedFiles = [];
let maxFiles = 5;
let allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
let maxFileSize = 5 * 1024 * 1024; // 5MB

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
});

// Formular initialisieren
function initializeForm() {
    // Zeichenzähler für Textarea
    initCharCounter();
    
    // Drag & Drop für Bilder
    initImageUpload();
    
    // Bestellnummer-Auswahl für eingeloggte Kunden
    initOrderSelection();
    
    // Formular-Validierung
    initFormValidation();
}

// Zeichenzähler initialisieren
function initCharCounter() {
    const textarea = document.getElementById('problem_beschreibung');
    const counter = document.getElementById('char_count');
    
    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            counter.textContent = count;
            
            // Warnung bei Überschreitung
            if (count > 2000) {
                counter.style.color = '#e74c3c';
                this.style.borderColor = '#e74c3c';
            } else if (count > 1800) {
                counter.style.color = '#f39c12';
                this.style.borderColor = '#f39c12';
            } else {
                counter.style.color = '#2c3e50';
                this.style.borderColor = '#bdc3c7';
            }
        });
    }
}

// Bildupload initialisieren
function initImageUpload() {
    const uploadArea = document.getElementById('upload_area');
    const fileInput = document.getElementById('bilder');
    const previewContainer = document.getElementById('preview_container');
    
    if (!uploadArea || !fileInput || !previewContainer) return;
    
    // Drag & Drop Events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        handleFileSelection(files);
    });
    
    // File Input Change
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        handleFileSelection(files);
    });
    
    // Click auf Upload-Bereich
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
}

// Dateiauswahl verarbeiten
function handleFileSelection(files) {
    files.forEach(file => {
        if (selectedFiles.length >= maxFiles) {
            showMessage('Maximal ' + maxFiles + ' Bilder erlaubt.', 'error');
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            showMessage('Nur JPG und PNG Dateien erlaubt: ' + file.name, 'error');
            return;
        }
        
        if (file.size > maxFileSize) {
            showMessage('Datei zu groß (max. 5MB): ' + file.name, 'error');
            return;
        }
        
        selectedFiles.push(file);
        createPreview(file, selectedFiles.length - 1);
    });
    
    updateFileInput();
}

// Bildvorschau erstellen
function createPreview(file, index) {
    const previewContainer = document.getElementById('preview_container');
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.innerHTML = `
            <img src="${e.target.result}" alt="Vorschau">
            <button type="button" class="preview-remove" onclick="removeFile(${index})" title="Entfernen">
                <span class="icon-remove"></span>
            </button>
        `;
        
        previewContainer.appendChild(previewItem);
    };
    
    reader.readAsDataURL(file);
}

// Datei entfernen
function removeFile(index) {
    selectedFiles.splice(index, 1);
    updatePreviews();
    updateFileInput();
}

// Vorschauen aktualisieren
function updatePreviews() {
    const previewContainer = document.getElementById('preview_container');
    previewContainer.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        createPreview(file, index);
    });
}

// File Input aktualisieren
function updateFileInput() {
    const fileInput = document.getElementById('bilder');
    const dt = new DataTransfer();
    
    selectedFiles.forEach(file => {
        dt.items.add(file);
    });
    
    fileInput.files = dt.files;
}

// Bestellauswahl für eingeloggte Kunden
function initOrderSelection() {
    const orderSelect = document.getElementById('bestellnummer_select');
    if (!orderSelect) return;
    
    // Event Listener bereits im HTML definiert
}

// Bestellprodukte laden (AJAX)
function loadOrderProducts(orderId) {
    if (!orderId) {
        document.getElementById('bestellnummer').value = '';
        document.getElementById('produkt_liste').innerHTML = `
            <input type="text" name="produkt_name" id="produkt_name" maxlength="255" class="form-control" placeholder="Produktname manuell eingeben">
            <input type="hidden" name="produkt_id" id="produkt_id">
        `;
        return;
    }
    
    // Bestellnummer setzen
    document.getElementById('bestellnummer').value = orderId;
    
    // AJAX-Request für Produktliste
    fetch('reklamation_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_order_products&order_id=' + encodeURIComponent(orderId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateProductList(data.products);
        } else {
            showMessage('Fehler beim Laden der Produkte: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        showMessage('Fehler beim Laden der Produkte.', 'error');
    });
}

// Produktliste aktualisieren
function updateProductList(products) {
    const produktListe = document.getElementById('produkt_liste');
    
    if (products.length === 0) {
        produktListe.innerHTML = `
            <input type="text" name="produkt_name" id="produkt_name" maxlength="255" class="form-control" placeholder="Keine Produkte gefunden">
            <input type="hidden" name="produkt_id" id="produkt_id">
        `;
        return;
    }
    
    let selectHtml = '<select name="produkt_id" id="produkt_id" class="form-control" onchange="updateProductName()">';
    selectHtml += '<option value="">Bitte wählen Sie ein Produkt</option>';
    
    products.forEach(product => {
        selectHtml += `<option value="${product.id}" data-name="${product.name}">${product.name} (${product.model})</option>`;
    });
    
    selectHtml += '</select>';
    selectHtml += '<input type="hidden" name="produkt_name" id="produkt_name">';
    
    produktListe.innerHTML = selectHtml;
}

// Produktname aktualisieren
function updateProductName() {
    const select = document.getElementById('produkt_id');
    const nameInput = document.getElementById('produkt_name');
    
    if (select && nameInput) {
        const selectedOption = select.options[select.selectedIndex];
        nameInput.value = selectedOption.getAttribute('data-name') || '';
    }
}

// Formular-Validierung
function initFormValidation() {
    const form = document.getElementById('reklamation_form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        // Loading-Spinner anzeigen
        showLoadingSpinner();
    });
}

// Formular validieren
function validateForm() {
    let isValid = true;
    const errors = [];
    
    // Pflichtfelder prüfen
    const requiredFields = [
        { id: 'kunde_name', name: 'Name' },
        { id: 'kunde_email', name: 'E-Mail-Adresse' },
        { id: 'bestellnummer', name: 'Bestellnummer' },
        { id: 'problem_beschreibung', name: 'Problembeschreibung' }
    ];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        if (!element || !element.value.trim()) {
            errors.push(field.name + ' ist ein Pflichtfeld.');
            isValid = false;
            if (element) {
                element.style.borderColor = '#e74c3c';
            }
        } else if (element) {
            element.style.borderColor = '#bdc3c7';
        }
    });
    
    // E-Mail-Format prüfen
    const email = document.getElementById('kunde_email');
    if (email && email.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            errors.push('Bitte geben Sie eine gültige E-Mail-Adresse ein.');
            email.style.borderColor = '#e74c3c';
            isValid = false;
        }
    }
    
    // Problembeschreibung Länge prüfen
    const description = document.getElementById('problem_beschreibung');
    if (description && description.value.length > 2000) {
        errors.push('Die Problembeschreibung darf maximal 2000 Zeichen lang sein.');
        description.style.borderColor = '#e74c3c';
        isValid = false;
    }
    
    // Datenschutz-Checkbox prüfen
    const datenschutz = document.getElementById('datenschutz');
    if (!datenschutz || !datenschutz.checked) {
        errors.push('Bitte stimmen Sie der Datenschutzerklärung zu.');
        isValid = false;
    }
    
    // Fehler anzeigen
    if (!isValid) {
        showMessage(errors.join('<br>'), 'error');
        scrollToTop();
    }
    
    return isValid;
}

// Loading-Spinner anzeigen
function showLoadingSpinner() {
    const submitBtn = document.getElementById('submit_btn');
    const spinner = document.getElementById('loading_spinner');
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
    }
    
    if (spinner) {
        spinner.style.display = 'inline-flex';
    }
}

// Nachricht anzeigen
function showMessage(message, type = 'info') {
    // Bestehende Nachrichten entfernen
    const existingMessages = document.querySelectorAll('.error-message, .success-message, .info-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Neue Nachricht erstellen
    const messageDiv = document.createElement('div');
    messageDiv.className = type + '-message';
    messageDiv.innerHTML = message;
    
    // Am Anfang des Formulars einfügen
    const form = document.getElementById('reklamation_form');
    if (form) {
        form.insertBefore(messageDiv, form.firstChild);
    }
    
    // Nach 5 Sekunden automatisch entfernen (außer bei Fehlern)
    if (type !== 'error') {
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
}

// Zum Seitenanfang scrollen
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Hilfsfunktionen für bessere UX
document.addEventListener('DOMContentLoaded', function() {
    // Automatisches Speichern in localStorage (optional)
    const form = document.getElementById('reklamation_form');
    if (form) {
        // Formular-Daten beim Eingeben speichern
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Daten aus localStorage laden
            const savedValue = localStorage.getItem('reklamation_' + input.name);
            if (savedValue && input.type !== 'file' && input.type !== 'checkbox') {
                input.value = savedValue;
            }
            
            // Daten beim Ändern speichern
            input.addEventListener('input', function() {
                if (this.type !== 'file' && this.type !== 'checkbox') {
                    localStorage.setItem('reklamation_' + this.name, this.value);
                }
            });
        });
        
        // localStorage nach erfolgreichem Absenden leeren
        form.addEventListener('submit', function() {
            inputs.forEach(input => {
                localStorage.removeItem('reklamation_' + input.name);
            });
        });
    }
});

