<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login to INVO Generator</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Helvetica:wght@400;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" />
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="lognav.css" />
</head>

<body>
  <div class="main-container">
    <?php include "lognav.php" ?>
    <div class="frame-8">
      <div class="frame-9">
        <div class="frame-a" id="loginDiv" data-animate-on-scroll>
          <div class="frame-b">
            <div class="frame-c">
              <span class="welcome-back">Welcome back!</span><span class="start-creating">Start Creating your digital
                invoice today
              </span>
            </div>
            <div class="frame-d">
              <div class="frame-e">
                <div class="frame-f">
                  <div class="frame-10">
                    <div class="frame-11">
                      <div class="mail"></div>
                    </div>
                    <input class="frame-12" placeholder="yourmail@gmail.com" type="text" id="email">
                  </div>
                  <div class="frame-13">
                    <div class="frame-15">
                      <div class="frame-16">
                        <div class="frame-17">
                          <div class="square-lock-password"></div>
                        </div>
                        <input class="frame-18" placeholder="password" type="password" id="password">
                      </div>
                      <div class="frame-19">
                        <div class="icons-eye"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="frame-1a">
                  <span class="forgot-password">Forgot password?</span>
                </div>
                <div class="frame-1b">
                  <span class="incorrect-email-password" id="validationMessages"></span>
                </div>
              </div>
              <button class="frame-1c" id="loginButton">
                <span class="login-1d">Login</span>
              </button>
            </div>
          </div>
          <div class="frame-1e">
            <div class="frame-1f">
              <div class="line"></div>
              <span class="or">or</span>
              <div class="line-20"></div>
            </div>
            <div class="frame-21">
              <button class="frame-22">
                <div class="icons-google"></div>
                <span class="google">Google</span>
              </button><button class="frame-23">
                <div class="icons-facebook"></div>
                <span class="facebook">Facebook</span>
              </button>
            </div>
            <div class="frame-24">
              <div class="frame-25">
                <span class="dont-have-account">Don’t you have an account?
                </span>
              </div>
              <div class="frame-26">
                <span class="sign-up-27">Sign Up</span>
              </div>
            </div>
          </div>
        </div>
        <div class="frame-28"></div>
        <div class="frame-29" id="signupDiv" data-animate-on-scroll>
          <div class="frame-2a">
            <div class="frame-2b">
              <span class="welcome-to-invo">Welcome to Invo Generator !</span><span class="start-creating-invoice">Start
                Creating your digital invoice today
              </span>
            </div>
            <div class="frame-2c">
              <div class="frame-2d">
                <div class="frame-2e">
                  <div class="frame-2f">

                    <div class="frame-31">
                      <div class="mail-32"></div>
                    </div>
                    <div class="frame-33">
                      <input class="frame-18" placeholder="yourmail@gmail.com" type="email" id="signup_email">
                    </div>
                  </div>
                  <div class="frame-35">

                    <div class="frame-37">
                      <div class="frame-38">
                        <div class="frame-39">
                          <div class="square-lock-password-3a"></div>
                        </div>
                        <input class="frame-18" placeholder="password" type="password" id="signup_password">
                      </div>
                      <div class="frame-3d">
                        <div class="icons-eye-3e"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="frame-3f">
                  <span class="incorrect-email-password-40" id="signupValidation"></span>
                </div>
              </div>
              <button class="button-frame" id="signupButton">
                <span class="sign-up-41">Sign up</span>
              </button>
            </div>
          </div>
          <div class="frame-42">
            <div class="frame-43">
              <div class="line-44"></div>
              <span class="or-45">or</span>
              <div class="line-46"></div>
            </div>
            <div class="frame-47">
              <button class="button-frame-48">
                <div class="icons-google-49"></div>
                <span class="google-4a">Google</span>
              </button><button class="button-frame-4b">
                <div class="icons-facebook-4c"></div>
                <span class="facebook-4d">Facebook</span>
              </button>
            </div>
            <div class="frame-4e">
              <div class="frame-4f">
                <span class="dont-have-account-50">Don’t you have an account?
                </span>
              </div>
              <div class="frame-51"><span class="login-52">Login</span></div>
            </div>
          </div>
        </div>

        <div class="c-frame-27" id="completeDiv">
          <div class="c-frame-28">
            <div class="c-frame-29">
              <span class="c-know-details">Let’s Know Few more Details</span>
              <span class="c-create-invoice">
                Complete signup and create your invoice today
              </span>
            </div>
            <div class="c-frame-2a">
              <div class="c-frame-2b">
                <div class="c-frame-2c">
                  <div class="c-frame-2d">
                    <div class="c-frame-2e">
                      <div class="c-user-square"></div>
                    </div>
                    <div class="c-frame-2f">
                      <input class="c-tell-us-full-name" type="text" placeholder="Tell us your full name?" id="user_name">
                    </div>
                  </div>
                </div>
              </div>
              <div class="c-frame-30">
                <div class="c-frame-31">
                  <div class="c-frame-32">
                    <div class="c-frame-33">
                      <div class="c-whatsapp-business"></div>
                    </div>
                    <div class="c-frame-34">
                      <input class="c-own-a-company" type="text" placeholder="Own a Company? (optional)">
                    </div>
                </div>
            </div>
          </div>
          <button class="c-frame-35" id="completeBtn">
            <span class="c-complete-signup">Complete Signup</span>
          </button>
        </div>
      </div>
    </div>



  </div>
  </div>
  </div>
  <script src="../myjs/eventhandling.js"></script>
  <script src="../myjs/loginHandler.js"></script>
  <script src="../myjs/signupHandler.js"></script>
</body>

</html>