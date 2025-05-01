<?php
namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'type' => 'Terms & Conditions',
            'text' => '
                <h1>Terms and Conditions</h1>
                <p>By accessing or using our platform, you agree to be bound by the following terms and conditions. Please read them carefully.</p>

                <h2>1. Acceptance of Terms</h2>
                <p>By registering or using any part of our services, you accept these terms and agree to comply with them.</p>

                <h2>2. User Responsibilities</h2>
                <p>You agree to use the platform lawfully, not to violate any applicable laws, and to ensure that your use does not harm others or the platform.</p>

                <h2>3. Account Security</h2>
                <p>You are responsible for maintaining the confidentiality of your login credentials. Notify us immediately if you suspect unauthorized access.</p>

                <h2>4. Termination</h2>
                <p>We reserve the right to suspend or terminate your account if you violate these terms.</p>

                <h2>5. Modifications</h2>
                <p>We may update these terms at any time. Continued use of the platform after changes means you accept the updated terms.</p>

                <p>For questions or concerns, please <a href="contact-us">contact us</a>.</p>
            ',
        ]);

        Page::create([
            'type' => 'Privacy Policy',
            'text' => '
                <h1>Privacy Policy</h1>
                <p>Your privacy is important to us. This policy explains how we handle your personal data.</p>

                <h2>1. Information We Collect</h2>
                <p>We collect information you provide (like name, email), and data from your usage of the platform (like browser type and access times).</p>

                <h2>2. How We Use Your Information</h2>
                <p>We use your data to operate the platform, personalize your experience, communicate updates, and improve services.</p>

                <h2>3. Sharing of Information</h2>
                <p>We do not sell your data. We may share it with service providers under strict confidentiality agreements.</p>

                <h2>4. Cookies</h2>
                <p>We use cookies to enhance functionality and track usage patterns. You may disable cookies via your browser settings.</p>

                <h2>5. Data Security</h2>
                <p>We use industry-standard measures to protect your information from unauthorized access.</p>

                <h2>6. Your Rights</h2>
                <p>You may request access to, correction of, or deletion of your personal data at any time.</p>

                <h2>7. Changes to Policy</h2>
                <p>We may revise this policy. Updates will be posted on this page with the revised date.</p>

                <p>If you have any questions, feel free to <a href="contact-us">contact us</a>.</p>
            ',
        ]);
        Page::create([
            'type' => 'About Us',
            'text' => '
                <h1>About Us</h1>
                <p>Welcome to our platform! We are a dedicated team of professionals committed to delivering exceptional services that simplify and enhance your experience.</p>

                <h2>Our Mission</h2>
                <p>To innovate and provide user-friendly solutions that empower individuals and businesses alike.</p>

                <h2>Our Vision</h2>
                <p>We strive to become a leading platform known for reliability, quality service, and customer satisfaction.</p>

                <h2>What We Offer</h2>
                <ul>
                    <li>Reliable and high-quality digital services</li>
                    <li>Responsive customer support available 24/7</li>
                    <li>Customizable features to meet diverse needs</li>
                    <li>Continuous updates and improvements</li>
                </ul>

                <h2>Why Choose Us?</h2>
                <p>Our platform is designed with the user in mind. We prioritize ease of use, security, and transparency in everything we do.</p>

                <p>If you have any questions or want to learn more, feel free to <a href="contact-us">contact us</a>. We look forward to serving you!</p>
            ',
        ]);

    }
}
