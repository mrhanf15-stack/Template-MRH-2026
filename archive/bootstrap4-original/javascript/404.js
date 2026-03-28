document.addEventListener("DOMContentLoaded", () => {
    const plants = document.querySelectorAll(".plant"); // These are now <img> tags
    const animationArea = document.querySelector(".animation-area");
    const h1Message = document.querySelector(".message-area h1");

    if (!animationArea) {
        console.error("Animation area not found!");
        return;
    }

    if (plants.length === 0) {
        console.error("No plant images found!");
        return;
    }

    // Add hover effect for each plant image
    plants.forEach(plant => {
        plant.style.transition = "transform 0.3s ease-out"; // For hover effect
        plant.addEventListener("mouseenter", () => {
            // Slightly enlarge and wiggle on hover
            const currentTransform = plant.style.transform || "";
            // Check if scale is already applied by animation, if so, augment it
            if (currentTransform.includes("scale(")) {
                 plant.style.transform = currentTransform.replace(/scale\([^)]*\)/, `scale(1.15) rotate(${(Math.random() - 0.5) * 5}deg)`);
            } else {
                plant.style.transform = `${currentTransform} scale(1.15) rotate(${(Math.random() - 0.5) * 5}deg)`;
            }
        });

        plant.addEventListener("mouseleave", () => {
            // The continuous animation will smooth this out / override.
            // We just remove the specific hover scale/rotation part if it was added like that.
            // This relies on the animatePlants function to correctly set the base transform.
            // A more robust solution would involve storing pre-hover transform.
            const currentTransform = plant.style.transform || "";
            plant.style.transform = currentTransform.replace(/scale\(1\.15\) rotate\([^)]*\)/, "scale(1)"); // Attempt to reset, animation loop will take over.
        });
    });

    const plantData = Array.from(plants).map((plant, index) => ({
        element: plant,
        angle: Math.random() * 8 - 4,      // Initial angle -4 to 4 deg for a gentler sway
        scale: 1 + (Math.random() - 0.5) * 0.05, // Initial scale 0.975 to 1.025
        angleOffset: Math.random() * Math.PI * 2, // Phase offset for individual movement
        scaleOffset: Math.random() * Math.PI * 2,
        baseScale: 1 // Store a base scale to return to after hover
    }));

    let time = 0;

    function animatePlants() {
        time += 0.02; // Increment time for smooth oscillation

        plantData.forEach(p => {
            // Do not interfere if the element is being hovered (basic check)
            let isHovered = (p.element.style.transform.includes("scale(1.15)"));

            // Swaying animation (rotation)
            const targetAngle = Math.sin(time + p.angleOffset) * (5 + Math.random() * 3); // Sway between -X and +X deg
            p.angle += (targetAngle - p.angle) * 0.05; // Smooth transition to target angle

            // Breathing animation (scaling)
            const targetScale = p.baseScale + Math.sin(time * 0.6 + p.scaleOffset) * (0.03 + Math.random() * 0.02); // Scale gently
            p.scale += (targetScale - p.scale) * 0.05; // Smooth transition to target scale
            
            if (!isHovered) { // Only apply animation transform if not hovered, or merge carefully
                 p.element.style.transform = `rotate(${p.angle}deg) scale(${p.scale})`;
            } else {
                // If hovered, the hover effect has priority on scale/immediate rotation.
                // We might want to still apply the base angle from the animation.
                const currentHoverTransform = p.element.style.transform;
                if (currentHoverTransform.includes("rotate(")) {
                     p.element.style.transform = currentHoverTransform.replace(/rotate\([^)]*\)/, `rotate(${p.angle}deg)`);
                } else {
                     p.element.style.transform = `rotate(${p.angle}deg) ${currentHoverTransform}`;
                }
            }
        });

        requestAnimationFrame(animatePlants);
    }

 // Start the animation
// Start the animation
animatePlants();

const h2Message = document.querySelector(".message-area h2");
if (h2Message) {
    // Text mit fixen Zeilenumbrüchen
    const fullText = "Oops!<br>Diese Seite ist<br>im Rauch aufgegangen.";

    h2Message.innerHTML = ""; // Inhalt leeren für Animation

    let charIndex = 0;

    function typeChar() {
        if (charIndex < fullText.length) {
            // Prüfen, ob ein <br> Tag an dieser Stelle beginnt
            if (fullText.substring(charIndex, charIndex + 4) === "<br>") {
                h2Message.innerHTML += "<br>";
                charIndex += 4;
            } else {
                // Normales Zeichen hinzufügen
                h2Message.innerHTML += fullText.charAt(charIndex);
                charIndex++;
            }

            // Nächster Buchstabe mit Zufallsverzögerung
            setTimeout(typeChar, 80 + Math.random() * 40);
        }
    }

    setTimeout(typeChar, 400); // Startverzögerung
}



    // Funny 404 text interaction
    if(h1Message){
        h1Message.style.cursor = "help";
        const originalText = h1Message.innerText;
        const funnyTexts = ["420?", "Lost?", "Puff?", "High?", "Oops!", "Error"];
        let clickCount = 0;
        h1Message.addEventListener("click", () => {
            clickCount++;
            const randomFunnyText = funnyTexts[Math.floor(Math.random() * funnyTexts.length)];
            h1Message.innerText = randomFunnyText;
            
            // Add a little shake animation
            h1Message.style.animation = "shake 0.5s cubic-bezier(.36,.07,.19,.97) both";
            setTimeout(() => { 
                h1Message.style.animation = ""; 
                // Optionally reset to original after a few clicks or if same text is shown
                if(clickCount > funnyTexts.length * 1.5 || h1Message.innerText === originalText) { // Reset sooner
                    h1Message.innerText = originalText;
                    clickCount = 0;
                }
            }, 500);
        });
    }
});

// Note: The @keyframes shake is expected to be in style.css
// If it wasn't added there, the JS attempt to add it might still be needed from the previous version.
// For robustness, ensure shake keyframes are in style.css.
