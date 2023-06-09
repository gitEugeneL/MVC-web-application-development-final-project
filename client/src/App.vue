<template>
    <div id="app">
        <NavBar />
        <div class="main-wrapper">
            <router-view />
        </div>
    </div>
</template>


<script>
import NavBar from "@/components/Nav.vue";

export default {
    name: 'App',

    components: {NavBar}
}

</script>


<style>
    @import url('https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700,800');

    * {
        box-sizing: border-box;
    }

    body {
        display: flex;
        font-weight: 400;
        font-family: 'Fira Sans', sans-serif;
    }

    h1 h2 h3 h4 h5 h6, label, span {
        font-weight: 500;
    }

    body, html, #app, #root, .auth-wrapper {
        width: 100%;

    }
    #app {
        text-align: center;
    }

    .main-wrapper {

        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: left;
        margin-top: 100px;
    }

    .auth-inner, .wrapper {
        width: 450px;
        margin: auto;
        background: #fff;
        box-shadow: 0 14px 80px rgba(34, 35, 58, .2);
        padding: 45px 55px 45px 55px;
        border-radius: 15px;
        transition: all .3s;
    }

    .auth-inner h3 {
        text-align: center;
    }

    .form-control:focus {
        border: 2px solid #167bff;
        box-shadow: none;
    }

    .auth-wrapper h3, .wrapper h3 {
        text-align: center;
        margin: 0;
        line-height: 1;
        padding-bottom: 20px;
    }

    .wrapper {
        width: 550px;
        padding: 20px;
    }
</style>