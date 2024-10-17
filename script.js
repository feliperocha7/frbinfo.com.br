// Animação de entrada com GSAP
gsap.from("header", { duration: 1, y: -100, opacity: 0, ease: "power2.out" });
gsap.from(".intro h1", { duration: 1, y: -50, opacity: 0, delay: 0.5 });
gsap.from(".intro h2", { duration: 1, y: -50, opacity: 0, delay: 0.7 });
gsap.from(".btn", { duration: 1, y: 50, opacity: 0, delay: 0.9 });

// ScrollReveal para seções
ScrollReveal().reveal('#about', { delay: 200 });
ScrollReveal().reveal('#projects', { delay: 400 });
ScrollReveal().reveal('#contact', { delay: 600 });


