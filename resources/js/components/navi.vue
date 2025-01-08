<template>
        <div class="container-fluid">
            <a class="navbar-brand">주식망겜</a>
            <div class="d-flex">
                <a class="btn btn-primary" :href="loginLink">{{ loginText }}</a>
            </div>
        </div>
</template>
<script>
export default {
    data() {
        return {
            lists: [],
            loginText: "-",
            loginLink:"#",
        }
    },
    methods: {
        loginChange: function () {
            if (document.querySelector('nav').getAttribute('loginid') !== null) {
                this.loginText = "로그아웃";
                this.loginLink="/logout";
            } else {
                this.loginText = "로그인";
                this.loginLink="/login";
            }
        }
    },
    mounted() {
        this.loginChange();
    }
}
</script>
<style scoped>
@import 'bootstrap';
</style>