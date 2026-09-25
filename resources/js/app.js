document.addEventListener('DOMContentLoaded', () => {
    const statsSection = document.querySelector('#stats-section');
    const counters = document.querySelectorAll('.stat-value');

    if (!statsSection || !counters.length) {
        return;
    }

    let animated = false;

    const startCounters = () => {
        if (animated) return;

        animated = true;

        counters.forEach((counter) => {
            const target = Number(counter.dataset.target || 0);
            const duration = 1400;
            const startTime = performance.now();

            const animate = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const value = Math.floor(target * eased);

                counter.textContent = value.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };

            requestAnimationFrame(animate);
        });
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                startCounters();
                observer.disconnect();
            }
        });
    }, { threshold: 0.3 });

    observer.observe(statsSection);
});
