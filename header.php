<?php
    //error_reporting(0);
    if(session_status() === PHP_SESSION_NONE)
        session_start();
    if(!array_key_exists('pageName', $GLOBALS))
        $pageName = "System ogłoszeniowy";    
?>
<header>
    <nav class="navbar navbar-dark p-0">
        <div class="container-fluid">          
            <section class="h-100 d-flex align-items-center">
                <a class="navbar-brand rounded p-1 ms-2" id="logo" href="./" data-bs-html="true" title="<i><b>System ogłoszeniowy Vistaaa</b></i> - Strona główna" data-bs-custom-class="moveTooltip" data-bs-toggle="tooltip">
                    <img src="vistaaa_full_logo.png" class="d-none d-sm-block" height="50" alt="Logo systemu ogłoszeniowego Vistaaa">
                    <img src="img/vistaaa_small_logo.png" class="d-sm-none" height="50" alt="Logo systemu ogłoszeniowego Vistaaa">             
                </a>
                <span class="d-none d-md-block text-white fs-5 fw-semibold">| <?php echo  $pageName?></span>
            </section>
            <section data-bs-toggle="tooltip" title="Zarządzaj swoim kontem" class="my-2">
               <button type="button" class="commonButton d-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                    <span class='bi bi-person-circle fs-5 me-2 d-none d-sm-inline'></span>
                    <span class="d-none d-sm-inline">Konto</span>
                    <span class="bi bi-list fs-5 ms-0 ms-sm-2"></span>
                </button> 
            </section>
            <div class="offcanvas offcanvas-end text-bg-dark vh-100 rounded <?php if(isset($_SESSION["login_error"])){echo 'show ';} ?>" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header justify-content-end">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-toggle="tooltip" title="Zamknij" data-bs-custom-class='redTooltip'></button>
                </div>
                <div class="offcanvas-body">
                    <?php                 
                        if(isset($_SESSION["logged"]) && $_SESSION["logged"])
                        {
                            echo $_SESSION["logged"];
                            echo "<a href='logout.php' class='dangerButton mt-2 d-inline-block text-decoration-none'><i class='bi bi-door-closed-fill me-2'></i>Wyloguj</a>";
                        }
                        else
                        {
                            echo "<h5 class='text-light mt-3'>Witaj w Vistaaa! Wygląda na to, że nie jesteś zalogowany.</h5>";
                            echo "<h6 class='text-light mt-3'>Zaloguj się, by móc w pełni korzystać z systemu Vistaaa.</h6>";
                            if(isset($_SESSION["login_error"]))
                            {
                                echo $_SESSION["login_error"];
                                unset($_SESSION["login_error"]);
                            }                    
                            echo<<<form
                            <form class="mt-3" action="login.php" method="post">
                                <div class="position-relative formInput mt-3">                       
                                    <input type="email" id="email" name="email" minlength="3" maxlength="254" placeholder="E-mail" required class="rounded-4 border-0 w-100 py-2 px-3">
                                    <label for="email" class="position-absolute">E-mail</label>
                                </div>
                                <div class="position-relative formInput mt-3">                       
                                    <input type="password" id="password" name="password" minlength="8" maxlength="255" placeholder="Hasło" required class="rounded-4 border-0 w-100 py-2 px-3">
                                <label for="password" class="position-absolute">Hasło</label>
                            </div>
                                <button class="successButton mt-2" type="submit"><i class="bi bi-person-check me-2"></i>Zaloguj się</button>
                            </form>
                            form;
                            echo "<h6 class='text-light mt-3'>Nie masz jeszcze konta? Zarejestruj się za darmo jako firma lub użytkownik indywidualny.</h6>";
                            echo "<a href='registration.php' class='commonButton mt-2 d-inline-block text-decoration-none'><i class='bi bi-person-badge me-2'></i>Rejestracja</a>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php
    if($pageName != "System ogłoszeniowy")
        echo "<p class='d-md-none ms-2 mt-4 fs-3 fw-bold text-success' id='mobileHeader'>$pageName</p>";
?>