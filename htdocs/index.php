<?php
session_start();
if (isset($_SESSION['terms_of_use']) && $_SESSION['terms_of_use'] === true) {
    header('Location: login.php');
    exit();
}
if (isset($_REQUEST['agree']) && $_REQUEST['agree'] === 'true') {
    $_SESSION['terms_of_use'] = true;
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Andromeda — Terms of Use</title>
        <link rel="stylesheet" type="text/css" href="styles/default.css" />
        <link rel="stylesheet" type="text/css" href="styles/mainStyles.css" />
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
    </head>
    <body class="landing-body">
        <main class="landing">
            <section class="landing-panel">
                <header class="landing-header">
                    <img src="img/logo.png" alt="Andromeda" class="landing-logo" />
                    <div class="landing-intro">
                        <h1>Before you enter the command center</h1>
                        <p>
                            To protect the Andromeda community, every pilot must review and accept the rules that govern
                            our private server. Please take a moment to read the terms below.
                        </p>
                    </div>
                </header>
                <article class="terms-card" aria-labelledby="terms-heading">
                    <header class="terms-card__header">
                        <h2 id="terms-heading">Terms of Use</h2>
                        <p>Review the key points below. You’ll need to accept them to continue.</p>
                    </header>
                    <div class="terms-card__content" role="region" aria-label="Terms and policies">
                        <section class="terms-section">
                            <h3>1. Terms</h3>
                            <p>
                                By accessing this website, you agree to be bound by these Terms and Conditions of Use, all
                                applicable laws and regulations, and you accept responsibility for compliance with any
                                applicable local laws. If you disagree with any of these terms, please refrain from using the
                                site. Materials hosted here are shared for personal enjoyment—please respect all copyright
                                regulations in your jurisdiction.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>2. Use License</h3>
                            <p>
                                We grant you permission to temporarily download one copy of the materials (information or
                                software) on Andromeda’s website for personal, non-commercial and transitory viewing only.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>3. Disclaimer</h3>
                            <p>
                                The materials on Andromeda’s website are provided “as is”. Andromeda makes no warranties,
                                expressed or implied, and hereby disclaims and negates all other warranties, including without
                                limitation implied warranties or conditions of merchantability, fitness for a particular
                                purpose, or non-infringement of intellectual property. We cannot guarantee the accuracy or
                                reliability of the materials or any sites linked to this one.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>4. Limitations</h3>
                            <p>
                                In no event shall Andromeda or its suppliers be liable for any damages (including, without
                                limitation, damages for loss of data or profit, or due to business interruption) arising out
                                of the use or inability to use the materials on Andromeda’s website.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>5. Revisions and Errata</h3>
                            <p>
                                The materials appearing on Andromeda’s website could include technical, typographical, or
                                photographic errors. We may make changes to the materials at any time without notice, but we
                                do not make any commitment to update the materials on a scheduled basis.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>6. Links</h3>
                            <p>
                                Andromeda has not reviewed all of the sites linked to its website and is not responsible for
                                the contents of any such linked site. The inclusion of any link does not imply endorsement;
                                use of any linked website is at the user’s own risk.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>7. Site Modifications</h3>
                            <p>
                                Andromeda may revise these terms of use at any time without notice. By using this website you
                                agree to be bound by the current version of these terms.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>8. Governing Law</h3>
                            <p>
                                Any claim relating to Andromeda’s website shall be governed by the laws of Switzerland
                                without regard to its conflict of law provisions.
                            </p>
                        </section>
                        <section class="terms-section">
                            <h3>Copyright Protection</h3>
                            <p>
                                If you believe any materials accessible on or from the site infringe your copyright, you may
                                request removal by contacting us and providing the following information:
                            </p>
                            <ul class="terms-list">
                                <li>Identification of the copyrighted work that you believe to be copied.</li>
                                <li>Your name, address, telephone number, and e-mail address.</li>
                                <li>
                                    A statement that you have a good-faith belief that the complained of use is not
                                    authorized by the copyright owner, its agent, or the law.
                                </li>
                                <li>
                                    A statement that the information supplied is accurate and that, under penalty of perjury,
                                    you are authorized to act on behalf of the copyright owner.
                                </li>
                                <li>A physical or electronic signature of the copyright holder or authorized representative.</li>
                            </ul>
                        </section>
                        <section class="terms-section">
                            <h3>Privacy Policy</h3>
                            <p>
                                Your privacy is very important to us. To help you understand how we handle personal
                                information, we follow these principles:
                            </p>
                            <ul class="terms-list">
                                <li>We identify the purposes for which information is collected before or at the time of collection.</li>
                                <li>
                                    We collect and use personal information solely to fulfil those purposes specified by us
                                    and for compatible purposes, unless we obtain the consent of the individual concerned or
                                    as required by law.
                                </li>
                                <li>We retain personal information only as long as necessary for the fulfilment of those purposes.</li>
                                <li>
                                    We collect personal information by lawful and fair means, with the knowledge or consent of
                                    the individual concerned.
                                </li>
                                <li>
                                    Personal data should be relevant to the purposes for which it is to be used and should be
                                    accurate, complete, and up to date.
                                </li>
                                <li>
                                    We protect personal information by reasonable security safeguards against loss or theft as
                                    well as unauthorized access, disclosure, copying, use or modification.
                                </li>
                                <li>
                                    We make information about our policies and practices relating to the management of
                                    personal information readily available.
                                </li>
                            </ul>
                            <p>
                                We are committed to conducting our operations in accordance with these principles to ensure
                                that the confidentiality of personal information is protected and maintained.
                            </p>
                        </section>
                    </div>
                </article>
                <form class="terms-consent" action="index.php" method="post">
                    <label class="terms-checkbox">
                        <input type="checkbox" name="agree" value="true" required />
                        <span>I have read and agree to abide by the Andromeda rules.</span>
                    </label>
                    <button class="landing-button" type="submit">Continue to Andromeda</button>
                </form>
            </section>
            <footer class="landing-footer">
                <p>Andromeda is an independent project (non-profit goal) &copy; <?php echo date('Y'); ?>.</p>
                <p>
                    <a target="_blank" rel="noopener" href="http://darkorbit.com/">Dark Orbit</a> is a registered trademark of
                    <a target="_blank" rel="noopener" href="http://bigpoint.com/">BigPoint GmbH</a>. All rights belong to their
                    respective owners. Andromeda is not endorsed by or affiliated with BigPoint GmbH.
                </p>
            </footer>
        </main>
    </body>
</html>
