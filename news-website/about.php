<?php
require_once 'includes.php';
renderHeader('About Us', 'about');
?>

<div class="page-hero">
    <div class="container">
        <h1>About LankaTimes</h1>
        <p>Sri Lanka's trusted digital news platform since 2015</p>
    </div>
</div>

<div class="container">
    <div class="about-content">
        <div class="about-card">
            <h2>Who We Are</h2>
            <p>LankaTimes is an independent digital news organisation based in Colombo, committed to delivering accurate, fair, and timely journalism to Sri Lankans at home and abroad. Founded in 2015 by a group of senior journalists, we have grown into one of the country's most read online news platforms.</p>
            <p>We cover the full spectrum of Sri Lankan life — from parliamentary politics and Central Bank decisions to Test cricket, the Esala Perahera, and the stories of everyday people shaping this nation. We believe journalism done right is a public service, not a commodity.</p>
            <p>Our editorial independence is non-negotiable. LankaTimes has no political affiliations and accepts no government advertising. Our newsroom operates under a strict editorial charter that prioritises truth over speed, fairness over sensationalism, and accountability over access.</p>
        </div>

        <div class="about-card" id="team">
            <h2>Our Team</h2>
            <p style="margin-bottom:20px;">Our newsroom is staffed by experienced journalists, editors, and digital professionals based across Sri Lanka.</p>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">R</div>
                    <div class="team-name">Rohana Sirisena</div>
                    <div class="team-role">Editor-in-Chief</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">P</div>
                    <div class="team-name">Priyanka De Silva</div>
                    <div class="team-role">Deputy Editor</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">K</div>
                    <div class="team-name">Kasun Perera</div>
                    <div class="team-role">Political Correspondent</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">T</div>
                    <div class="team-name">Thilini Jayawardena</div>
                    <div class="team-role">Business Editor</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">D</div>
                    <div class="team-name">Dilshan Wickramasinghe</div>
                    <div class="team-role">Sports Editor</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">N</div>
                    <div class="team-name">Nadeeka Silva</div>
                    <div class="team-role">Technology Reporter</div>
                </div>
            </div>
        </div>

        <div class="about-card">
            <h2>Our Standards</h2>
            <p>We are guided by the following editorial principles:</p>
            <ul style="margin-left:20px;line-height:2.2;color:#444;font-size:15px;">
                <li>We verify information from at least two independent sources before publishing.</li>
                <li>Corrections are published promptly and transparently, without hiding errors.</li>
                <li>We clearly distinguish between news reporting and opinion/analysis.</li>
                <li>We do not accept payments for editorial coverage.</li>
                <li>We protect the identity of sources who face risk by speaking to us.</li>
            </ul>
            <p style="margin-top:16px;">To report a factual error or request a correction, email <a href="mailto:corrections@lankatimes.lk" style="color:#C41E1E;">corrections@lankatimes.lk</a>.</p>
        </div>
    </div>
</div>

<?php renderFooter(); ?>
