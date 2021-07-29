<style>

    * {
        margin:0px auto;
        padding: 0px;
        text-align:center;
    }
    body {
        background-color: #26a463;
    }
    .cont_principal {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .cont_error {
        position: absolute;
        width: 100%;
        height: 300px;
        top: 50%;
        margin-top:-150px;
    }

    .cont_error > h1  {
        font-family: 'Lato', sans-serif;
        font-weight: 400;
        font-size:150px;
        color: rgba(0, 0, 0, 0.36);
        position: relative;
        left:-100%;
        transition: all 0.5s;
    }


    .cont_error > p  {
        font-family: 'Lato', sans-serif;
        font-weight: 300;
        font-size:24px;
        letter-spacing: 5px;
        color: #000000;
        position: relative;
        left:100%;
        transition: all 0.5s;
        transition-delay: 0.5s;
        -webkit-transition: all 0.5s;
        -webkit-transition-delay: 0.5s;
    }

    .cont_aura_1 {
        position:absolute;
        width:300px;
        height: 120%;
        top:25px;
        right: -340px;
        background-color: #1792d6;
        box-shadow: 0px 0px  60px  20px rgb(38, 164, 99);
        -webkit-transition: all 0.5s;
        transition: all 0.5s;
    }

    a:link {
        color: #000000;
    }

    /* visited link */
    a:visited {
        color: #000000;
    }

    /* mouse over link */
    a:hover {
        color: blue;
    }

    /* selected link */
    a:active {
        color: blue;
    }

    .cont_aura_2 {
        position:absolute;
        width:100%;
        height: 300px;
        right:-10%;
        bottom:-301px;
        background-color: #ffe100;
        box-shadow: 0px 0px 60px 10px rgb(38, 164, 99),0px 0px  20px  0px  rgba(0,0,0,0.1);
        z-index:5;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
    }

    .cont_error_active > .cont_error > h1 {
        left:0%;
    }
    .cont_error_active > .cont_error > p {
        left:0%;
    }

    .cont_error_active > .cont_aura_2 {
        animation-name: animation_error_2;
        animation-duration: 4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        animation-direction: alternate;
        transform: rotate(-20deg);
    }
    .cont_error_active > .cont_aura_1 {
        transform: rotate(20deg);
        right:-170px;
        animation-name: animation_error_1;
        animation-duration: 4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        animation-direction: alternate;
    }

    @-webkit-keyframes animation_error_1 {
        from {
            -webkit-transform: rotate(20deg);
            transform: rotate(20deg);
        }
        to {  -webkit-transform: rotate(25deg);
            transform: rotate(25deg);
        }
    }
    @-o-keyframes animation_error_1 {
        from {
            -webkit-transform: rotate(20deg);
            transform: rotate(20deg);
        }
        to {  -webkit-transform: rotate(25deg);
            transform: rotate(25deg);
        }

    }
    @-moz-keyframes animation_error_1 {
        from {
            -webkit-transform: rotate(20deg);
            transform: rotate(20deg);
        }
        to {  -webkit-transform: rotate(25deg);
            transform: rotate(25deg);
        }

    }
    @keyframes animation_error_1 {
        from {
            -webkit-transform: rotate(20deg);
            transform: rotate(20deg);
        }
        to {  -webkit-transform: rotate(25deg);
            transform: rotate(25deg);
        }
    }




    @-webkit-keyframes animation_error_2 {
        from { -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }
        to { -webkit-transform: rotate(-20deg);
            transform: rotate(-20deg);
        }
    }
    @-o-keyframes animation_error_2 {
        from { -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }
        to { -webkit-transform: rotate(-20deg);
            transform: rotate(-20deg);
        }
    }

    @-moz-keyframes animation_error_2 {
        from { -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }
        to { -webkit-transform: rotate(-20deg);
            transform: rotate(-20deg);
        }
    }
    @keyframes animation_error_2 {
        from { -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }
        to { -webkit-transform: rotate(-20deg);
            transform: rotate(-20deg);
        }
    }


</style>


<div class="cont_principal">
    <div class="cont_error">

        <h1>Oops</h1>
        <p>An unexpected error has occurred, please go <a href="{{route('dashboard')}}">back</a> and try again</p>
    </div>
    <div class="cont_aura_1"></div>
    <div class="cont_aura_2"></div>
</div>

<script>
    window.onload = function(){
        document.querySelector('.cont_principal').className= "cont_principal cont_error_active";

    }
</script>
