<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="<?=url('src/view/login/index.css')?>">
    <title>Document</title>
    <style>
        #background-div{
            background-image: url('<?=url('/resources/campus_default_image.png')?>');
        }
    </style>
</head>
<body>
    <div class="content-container">

        <div id="background-div"></div>

        <div class="form-container">
            <div style="height: 17%;">
            <form method="POST" id="form" action='<?=url('')?>'>
                <div class="login-form-group">
                    <input class="form-login-input" name='lgn' type="text" id="lgn" required value='admin'>
                    <label class="form-login-input-label">Usuario</label>
                </div>
                <div class="login-form-group">
                    <input class="form-login-input" name='psw' type="password" id="psw" required value='admin'>
                    <label class="form-login-input-label">Senha</label>
                </div>
            </form>
            <p id="msg"></p>
                </div>
            <svg class="submit-button" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="submit-button-color" d="M63 31.5C63 48.897 48.897 63 31.5 63C14.103 63 0 48.897 0 31.5C0 14.103 14.103 0 31.5 0C48.897 0 63 14.103 63 31.5Z" fill="black"/>
                <path d="M58 31.5C58 46.1355 46.1355 58 31.5 58C16.8645 58 5 46.1355 5 31.5C5 16.8645 16.8645 5 31.5 5C46.1355 5 58 16.8645 58 31.5Z" fill="white"/>
                <path class="submit-button-color" d="M41.3884 32.9357L28.5651 45.4048C27.7494 46.1984 26.4269 46.1984 25.6115 45.4048C24.7962 44.6119 24.7962 43.3259 25.6115 42.5331L36.9581 31.4998L25.6119 20.4669C24.7965 19.6737 24.7965 18.3878 25.6119 17.5949C26.4273 16.8017 27.7497 16.8017 28.5654 17.5949L41.3887 30.0643C41.7964 30.4609 42 30.9802 42 31.4997C42 32.0195 41.796 32.5392 41.3884 32.9357Z" fill="black"/>
            </svg>
        </div>
    </div>
</body>
<script defer>
const login = document.getElementById('lgn')
const pass = document.getElementById('psw')
const msg = document.getElementById('msg')
document.querySelector('.submit-button').addEventListener('click',(event)=>{
    if (login.value == '' || pass.value == '') {
        msg.innerHTML = 'Please fill all the required fields'
        setTimeout(() => {
            msg.innerHTML = ''
        }, 5000);
        return
    }
    document.getElementById('form').submit()
})
</script>
</html>