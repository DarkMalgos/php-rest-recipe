<template>
  <header>
    <div class="logo">
      <img src="../assets/img/logo.svg" alt="logo">
    </div>
    <nav>
      <ul>
        <router-link tag="li" to="/">Les recettes</router-link>
        <router-link tag="li" to="/add">Ajoutez une recette</router-link>
      </ul>
    </nav>
  </header>
</template>

<script>
    export default {
        name: "Header",
        data() {
            return {
            }
        }
    }
</script>

<style scoped>
  header{
    display: flex;
    padding: 10px 2vw;
    align-items: center;
    justify-content: space-between;
    background-color: #fff;
  }

  nav ul{
    display: flex;
    list-style: none;
  }

  nav ul li{
    list-style: none;
    padding: 0 20px;
  }

  nav ul li a {
    color: #54486E;
  }

  .logo{
    width: 160px;
  }
</style>
