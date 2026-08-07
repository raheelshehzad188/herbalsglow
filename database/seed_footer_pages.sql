-- WellCenter.pk footer pages (Policies, Help, Information)

UPDATE pages SET name = 'Terms & Conditions', menu_type = 'footer_policies', position = 1, status = 1 WHERE slug = 'terms-conditions';
UPDATE pages SET name = 'Privacy Policy', menu_type = 'footer_policies', position = 2, status = 1 WHERE slug = 'privacy-policy';
UPDATE pages SET name = 'Return & Refund Policy', menu_type = 'footer_policies', position = 3, status = 1 WHERE slug = 'returns-exchange';
UPDATE pages SET name = 'Shipping & Delivery Policy', menu_type = 'footer_policies', position = 4, status = 1 WHERE slug = 'shipping';

UPDATE pages SET name = 'Contact Us', menu_type = 'footer_help', route = 'contact', position = 1, status = 1 WHERE slug = 'contact';
UPDATE pages SET name = 'Track Your Order', menu_type = 'footer_help', route = 'track_order', position = 3, status = 1 WHERE slug = 'order-tracking';

UPDATE pages SET name = 'About Us', menu_type = 'footer_information', route = NULL, position = 1, status = 1 WHERE slug = 'about';
UPDATE pages SET name = 'Payment Methods', menu_type = 'footer_information', position = 3, status = 1 WHERE slug = 'payment-method';

INSERT INTO pages (name, slug, menu_type, position, status, content, created_at, updated_at)
SELECT 'FAQs', 'faqs', 'footer_help', 2, 1,
'<h3>Frequently Asked Questions</h3><p><strong>Are your products authentic?</strong><br>Yes. WellCenter.pk sources products from trusted suppliers and authorized distributors.</p><p><strong>How long does delivery take?</strong><br>Most orders are delivered within 2 to 7 business days depending on your city.</p><p><strong>Can I return a product?</strong><br>Please review our Return &amp; Refund Policy for eligibility and steps.</p><p><strong>How do I track my order?</strong><br>Use the Track Your Order page with your order number.</p>',
NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'faqs');

INSERT INTO pages (name, slug, menu_type, position, status, content, created_at, updated_at)
SELECT 'How to Order', 'how-to-order', 'footer_help', 4, 1,
'<h3>How to Order on WellCenter.pk</h3><ol><li>Browse products and add items to your cart.</li><li>Open your cart and click Proceed to Checkout.</li><li>Enter your shipping details and choose a payment method.</li><li>Place your order and save your order number.</li><li>Track delivery using Track Your Order.</li></ol>',
NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'how-to-order');

INSERT INTO pages (name, slug, menu_type, position, status, content, created_at, updated_at)
SELECT 'Authenticity Guarantee', 'authenticity-guarantee', 'footer_information', 2, 1,
'<h3>Authenticity Guarantee</h3><p>WellCenter.pk is committed to offering genuine health, wellness, and beauty products. We work with verified suppliers and inspect product quality before dispatch.</p><p>If you believe a product is not authentic, contact our support team with your order details and we will investigate promptly.</p>',
NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'authenticity-guarantee');

UPDATE pages SET menu_type = 'footer_help', route = 'faq', position = 2, status = 1 WHERE slug = 'faqs';
