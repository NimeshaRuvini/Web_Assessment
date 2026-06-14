-- LankaTimes News Website Database
CREATE DATABASE IF NOT EXISTS lankatimes;
USE lankatimes;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    color VARCHAR(7) DEFAULT '#D32F2F',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image_url VARCHAR(500),
    category_id INT,
    author VARCHAR(100) DEFAULT 'LankaTimes Staff',
    is_featured TINYINT(1) DEFAULT 0,
    is_breaking TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    status ENUM('published', 'draft') DEFAULT 'published',
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Subscribers table
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA
-- ============================================================

INSERT INTO categories (name, slug, color) VALUES
('Politics', 'politics', '#C62828'),
('Economy', 'economy', '#1565C0'),
('Sports', 'sports', '#2E7D32'),
('Technology', 'technology', '#6A1B9A'),
('World', 'world', '#E65100'),
('Culture', 'culture', '#AD1457');

-- Default admin: admin / admin123
INSERT INTO admins (username, password, full_name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Site Administrator', 'admin@lankatimes.lk');

INSERT INTO articles (title, slug, excerpt, content, image_url, category_id, author, is_featured, is_breaking, views, status) VALUES

('President Dissanayake Meets IMF Officials to Discuss Economic Recovery Plan',
'president-dissanayake-imf-economic-recovery',
'President Anura Kumara Dissanayake held high-level discussions with IMF officials in Colombo, focusing on Sri Lanka\'s ongoing economic recovery and the conditions tied to the $2.9 billion bailout programme.',
'<p>President Anura Kumara Dissanayake met with senior International Monetary Fund (IMF) officials at the Presidential Secretariat in Colombo on Wednesday, in discussions centred on the progress of Sri Lanka\'s economic recovery programme.</p><p>The meeting, attended by Finance Ministry officials and the Central Bank Governor, reviewed key reform milestones under the $2.9 billion Extended Fund Facility agreed in 2023.</p><p>IMF Resident Representative stated that Sri Lanka has made "commendable progress" in stabilising its economy, noting improved foreign exchange reserves and a more disciplined fiscal approach under the current administration.</p><p>President Dissanayake reaffirmed the government\'s commitment to the reform agenda while emphasising the need to ease the burden on ordinary citizens through targeted welfare measures. He pointed to the Aswesuma social protection programme as a key pillar in cushioning vulnerable households from austerity measures.</p><p>"We are on track, but we must ensure that economic recovery translates into tangible relief for the people," the President stated. He also raised concerns about the debt restructuring timeline and pressed for flexibility in domestic revenue targets given the subdued global economic outlook.</p><p>The IMF team is expected to complete its fifth review of the programme by end of the month, which will unlock the next tranche of funding. Analysts say a successful review will boost investor confidence and could result in a credit rating upgrade for the island nation.</p>',
'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=800&q=80',
1, 'Kasun Perera', 1, 1, 1284, 'published'),

('Sri Lanka Cricket Board Announces Squad for England Test Series',
'sl-cricket-squad-england-test-series',
'Sri Lanka Cricket has named a 15-member squad for the upcoming three-match Test series against England, with Pathum Nissanka confirmed as captain following Dimuth Karunaratne\'s retirement.',
'<p>Sri Lanka Cricket (SLC) unveiled the national Test squad on Thursday for the eagerly anticipated home series against England, scheduled to begin next month at the Galle International Stadium.</p><p>Pathum Nissanka leads the side for the first time as permanent Test captain, after an impressive stint as stand-in leader during the South Africa series. The board confirmed that the selectors prioritised youth and match fitness in the squad selection.</p><p>Prabath Jayasuriya, Sri Lanka\'s leading spinner in recent years, headlines the bowling attack alongside uncapped left-arm spinner Nishan Peiris, who has been rewarded for a stellar domestic season. Asitha Fernando and Lahiru Kumara form the pace battery.</p><p>Notably absent is veteran wicketkeeper Niroshan Dickwella, who was overlooked in favour of Sadeera Samarawickrama after a string of underwhelming performances in the longer format.</p><p>Chief Selector Upul Tharanga said the panel was confident the selected group could exploit Sri Lanka\'s traditionally spin-friendly pitches to challenge England\'s famed "Bazball" approach. "Galle has always been a fortress for us. We are well prepared," he said.</p><p>The first Test begins on 14 July, with the remaining two matches at R. Premadasa Stadium in Colombo. The series carries significant ICC World Test Championship points for both nations.</p>',
'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=800&q=80',
3, 'Dilshan Wickramasinghe', 1, 0, 3891, 'published'),

('Colombo Port City Attracts $450 Million in New Foreign Investment',
'colombo-port-city-450-million-investment',
'The Colombo Port City Economic Commission announced fresh foreign direct investment commitments totalling $450 million from financial services and technology companies seeking to establish regional headquarters.',
'<p>The Colombo Port City Economic Commission (CPCEC) announced on Tuesday that it has secured foreign direct investment commitments worth $450 million from eight new companies, including two multinational financial services firms and a South Korean technology conglomerate.</p><p>CPCEC Chairman Dr. Rangi Asanga said the latest wave of interest reflects renewed global confidence in Sri Lanka as a strategic hub in South Asia. "Port City is emerging as the gateway to the Indian Ocean economy," he noted at a press briefing in Colombo.</p><p>The financial services firms — a Singapore-based asset management company and a UK-registered fintech — are set to register under the offshore business company category, taking advantage of Port City\'s zero income tax rate for the first 25 years. The South Korean firm, a subsidiary of a major electronics group, plans to set up its South Asia logistics and distribution headquarters within the special economic zone.</p><p>Sri Lanka\'s Finance Ministry welcomed the announcement, stating it would generate over 3,000 direct employment opportunities in white-collar sectors. Critics, however, have called for greater transparency in concession agreements and more safeguards for local businesses operating in proximity to the zone.</p><p>Port City currently has over 60 registered businesses and 11 completed buildings, with construction ongoing on another 18 developments. The zone targets $15 billion in cumulative investment over the next decade.</p>',
'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',
2, 'Thilini Jayawardena', 1, 0, 2107, 'published'),

('Tech Startup Ecosystem in Colombo Sees Record Growth in 2025',
'colombo-tech-startup-ecosystem-2025',
'A new report by the Information and Communication Technology Agency reveals that Sri Lanka\'s startup ecosystem raised over Rs. 8 billion in funding in 2025, with fintech and agritech leading the charge.',
'<p>Sri Lanka\'s technology startup ecosystem achieved a landmark year in 2025, raising a record Rs. 8.2 billion in total funding across 47 deals, according to a report released by the Information and Communication Technology Agency of Sri Lanka (ICTA).</p><p>The report, titled "Sri Lanka Startup Landscape 2025," highlights that fintech startups accounted for the largest share at 34%, followed by agritech at 21% and healthtech at 15%. Several companies successfully attracted Series A and Series B rounds from regional venture capital firms based in Singapore and Dubai.</p><p>Irosha Fernando, CEO of Trace Expert City in Colombo — the country\'s premier tech hub — said the momentum reflects a maturing ecosystem. "Five years ago, we were talking about potential. Now we\'re talking about scale," she said.</p><p>Notable deals include a $4 million investment in PayLink, a micro-payment platform targeting unbanked populations in rural Sri Lanka, and a $3.5 million raise by GreenHarvest, an AI-driven platform connecting farmers directly with export buyers.</p><p>ICTA also noted a significant uptick in developer talent retention, with emigration among software engineers falling for the first time since the 2022 economic crisis. Government incentives, including tax holidays for registered startups and co-working space subsidies, were credited as key factors.</p><p>The agency targets $50 million in startup funding by 2027, backed by a proposed national venture fund to be co-managed with private investors.</p>',
'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80',
4, 'Nadeeka Silva', 0, 0, 1543, 'published'),

('Kandy Esala Perahera Draws 1.2 Million Visitors, Boosting Tourism Revenue',
'kandy-esala-perahera-12-million-visitors',
'The annual Kandy Esala Perahera concluded with record attendance, drawing over 1.2 million visitors including 180,000 foreign tourists, generating an estimated Rs. 4.5 billion in tourism income for the Central Province.',
'<p>The 2025 Kandy Esala Perahera, one of Asia\'s most spectacular religious processions, concluded on Sunday after ten nights of pageantry, drawing a record 1.2 million visitors to the Hill Capital, according to the Sri Lanka Tourism Development Authority (SLTDA).</p><p>Of the total, approximately 180,000 were international tourists from 54 countries, representing a 28% increase over the previous year. The UK, Germany, Australia, India, and China were the top source markets. The SLTDA estimates the event generated Rs. 4.5 billion in direct and indirect tourism revenue for the Central Province.</p><p>The Perahera, held in honour of the Sacred Tooth Relic of the Buddha enshrined at the Sri Dalada Maligawa, featured 117 traditionally adorned elephants, hundreds of Kandyan dancers, fire-twirlers, and drummers parading through the streets of the ancient city each evening.</p><p>Chief Custodian of the Dalada Maligawa, Diyawadana Nilame Pradeep Nilanga Dela, expressed satisfaction with the event\'s organisation and the spiritual atmosphere that prevailed throughout. "The Perahera is not merely a cultural spectacle. It is a living expression of our Buddhist heritage and national identity," he said.</p><p>The Tourism Minister called for improved infrastructure around Kandy to handle the growing visitor volumes sustainably, flagging traffic management and solid waste disposal as areas requiring urgent investment ahead of next year\'s event.</p>',
'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',
6, 'Malini Bandara', 0, 0, 4210, 'published'),

('UN Report Ranks Sri Lanka Among Top Improvers in Human Development Index',
'sri-lanka-un-human-development-index-2025',
'The United Nations Development Programme\'s latest Human Development Report places Sri Lanka as one of the top five most improved nations globally, citing advances in education access and healthcare outcomes.',
'<p>Sri Lanka has been ranked among the top five most improved nations in the world in the 2025 Human Development Report published by the United Nations Development Programme (UNDP), a significant recognition for a country that faced severe economic collapse just three years ago.</p><p>The report noted Sri Lanka\'s recovery in key social indicators including literacy rates, maternal mortality, access to clean water, and average years of schooling. Sri Lanka\'s Human Development Index (HDI) score rose from 0.716 in 2023 to 0.741 in 2025, placing it firmly in the "High Human Development" category.</p><p>UNDP Resident Representative in Sri Lanka, Dr. Azusa Kubota, described the progress as "remarkable given the depth of the crisis Sri Lanka endured." She attributed the gains to consistent public spending on health and education even during the austerity period, as well as improvements in women\'s economic participation.</p><p>The report does caution that inequality remains a concern, with the HDI adjusted for inequality dropping by 12.4%, one of the higher inequality discounts in the South Asia region. Rural-urban disparities, particularly in Uva and North Central provinces, are flagged as priority areas.</p><p>The government cited the report as validation of its human-centred development strategy and pledged to accelerate investment in vocational training and digital skills to sustain the upward trajectory.</p>',
'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&q=80',
5, 'Ruwan Jayasena', 0, 0, 987, 'published'),

('Central Bank Holds Interest Rates Steady Amid Falling Inflation',
'central-bank-holds-rates-inflation-falling',
'The Central Bank of Sri Lanka kept its key policy rates unchanged at its latest Monetary Policy Board meeting, citing declining inflation and the need to sustain economic momentum.',
'<p>The Central Bank of Sri Lanka (CBSL) announced on Friday that it would maintain its Standing Deposit Facility Rate (SDFR) at 8.00% and Standing Lending Facility Rate (SLFR) at 9.00%, following the monthly Monetary Policy Board meeting.</p><p>The decision was widely anticipated by economists, given that headline inflation has remained below 4% for four consecutive months — a stark contrast to the peak of 70% recorded in 2022 at the height of the economic crisis.</p><p>Governor Dr. Nandalal Weerasinghe said the Board assessed that current monetary conditions are "broadly supportive of the recovery trajectory" and that there was no immediate case for either tightening or easing. He noted that while private sector credit growth has picked up, it remains within manageable bounds.</p><p>The rupee has shown relative stability against the US dollar this year, trading in a narrow band between Rs. 295 and Rs. 305. Foreign exchange reserves have recovered to $4.7 billion, providing around 3.5 months of import cover — a significant improvement from the near-zero levels of 2022.</p><p>Economists note that while the macroeconomic picture has improved substantially, the transmission of lower policy rates to retail lending rates has been uneven. Mortgage rates and SME loan rates remain elevated, which the Governor acknowledged as an issue the banking sector needs to address proactively.</p>',
'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
2, 'Sachini Weerasekara', 0, 0, 1102, 'published'),

('Sri Lanka Wins Bronze at Commonwealth Weightlifting Championships',
'sri-lanka-bronze-commonwealth-weightlifting',
'Weightlifter Hiruni Nilukshi brought pride to the nation by clinching a bronze medal in the women\'s 59kg category at the Commonwealth Weightlifting Championships held in Auckland, New Zealand.',
'<p>Hiruni Nilukshi delivered a moment of national pride on Sunday by winning the bronze medal in the women\'s 59kg category at the 2025 Commonwealth Weightlifting Championships in Auckland, New Zealand — Sri Lanka\'s first medal in the event in six years.</p><p>The 24-year-old from Kurunegala successfully completed a total lift of 193kg, comprising a 87kg snatch and 106kg clean and jerk, finishing behind gold medallist Camilla Fogagnolo of Australia and silver medallist Harshada Garud of India.</p><p>Nilukshi, who trains at the Sri Lanka National Institute of Sport Science in Colombo, dedicated her medal to her late coach who passed away earlier this year. "This is for sir. He always believed I would make it to the podium," she said with visible emotion at the post-event press conference.</p><p>Sports Minister Harin Fernando congratulated Nilukshi on social media, calling her achievement "an inspiration for every young athlete in Sri Lanka." He also announced a cash award of Rs. 500,000 from the Ministry\'s sports excellence fund.</p><p>Nilukshi now sets her sights on the 2026 Commonwealth Games in Hamilton, New Zealand, where she aims to improve on her bronze medal performance. Sri Lanka Weightlifting Federation President said the federation will apply for additional government funding to support her preparation camp schedule.</p>',
'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80',
3, 'Chamath Gunawardena', 0, 0, 2340, 'published');

-- Sample comments
INSERT INTO comments (article_id, name, email, comment, status) VALUES
(1, 'Pradeep R.', 'pradeep@example.com', 'This is encouraging news. Hopefully the government can balance fiscal discipline with welfare needs.', 'approved'),
(1, 'Samanthi K.', NULL, 'We need more transparency about what exactly was discussed at this meeting.', 'approved'),
(2, 'Cricket Fan LK', NULL, 'Great to see Nissanka leading. He deserves it after his consistent performances.', 'approved'),
(2, 'Roshan M.', 'roshan@example.com', 'Missing Dickwella is a big risk. His experience behind the stumps is invaluable.', 'approved'),
(5, 'Chamara S.', NULL, 'Perahera is simply magical. Every Sri Lankan should experience it at least once.', 'approved');
