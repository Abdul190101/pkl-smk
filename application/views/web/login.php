<main id="hero" class="align-items-center bg-primary mb-5">
    <div class="container">
        <div class="row">

            <!-- Simple login form -->
            <form action="" method="post">
                <div class="panel panel-body login-form">
                    <div class="text-center"> <br> <br>
                        <h5 class="content-group text-white">HALAMAN LOGIN</h5>
                        <?php
                        echo $this->session->flashdata('msg');
                        ?>
                    </div> <br>

                    <div class="form-group has-feedback has-feedback-left">
                    <span class="text-white">Nis / Username</span>
                        <input type="text" class="form-control" name="username" placeholder="NIS / Username" required>
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div> <br>

                    <div class="form-group has-feedback has-feedback-left">
                        <span class="text-white">Password</span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div> <br>

                    <div class="form-group">
                        <div class="btn btn-info btn-block position-left">
                            <a href="web/" class="icon-circle-right2 position-right text-white">Kembali</a>
                        </div>
                        <button type="submit" name="btnlogin" class="btn btn-info btn-block text-white">Masuk <i class="icon-circle-right2 position-right"></i></button>
                    </div>
                </div>
            </form>

            <!-- /simple login form -->
        </div>
    </div>
</main>