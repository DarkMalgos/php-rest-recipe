<template>
  <div id="app">
    <Header></Header>
    <router-view/>
  </div>
</template>

<script>

  import Header from './components/Header'
export default {
  name: 'App',
    components: {
      Header
    }
}
</script>

<style>
  *{
    margin:0;
    padding: 0;
    font-family: Verdana, Geneva, Tahoma, sans-serif
  }

  body{
    font-size: 15px;
    background-color: #EFEAE6;
  }

  a{
    text-decoration: none;
  }

  img{
    width: 100%;
    height: auto;
  }
</style>
