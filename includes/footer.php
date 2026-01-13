<!-- Footer -->
<footer id="contact">
    <div class="container">
        <div class="footer-content">

            <!-- Contact Info -->
            <div class="footer-column">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+91 98765 43210</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@srselectrical.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            123 Industrial Area, Phase II<br>
                            Bengaluru, Karnataka 560058
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-column">
                <h3>Quick Links</h3>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact Us</a>
                <a href="products.php">Product Catalog</a>
                <a href="report.php">Testing Reports</a>
                <a href="dashboard.php">Dashboard</a>
            </div>

            <!-- Social Media -->
            <div class="footer-column">
                <h3>Connect With Us</h3>
                <p>
                    Follow us on social media for updates on electrical testing
                    standards and industry news.
                </p>

                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>
                &copy; 2023 SRS Electrical Appliances.
                All Rights Reserved |
                ISO 9001:2015 Certified |
                CPRI Approved Testing Facility
            </p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script>
    // Mobile Navigation Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    mobileToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        mobileToggle.innerHTML = navMenu.classList.contains('active')
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    });

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
        });
    });
</script>

</body>
</html>
