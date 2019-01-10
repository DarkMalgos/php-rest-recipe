<template>
  <main>
    <section class="bg-home">
      <h1>Blabla</h1>
    </section>
    <section class="section-middle">
      <router-link tag="div" v-for="(recipe, index) in recipes" :to="'/recipe/'+recipe.id" class="recipe" :key="index">
        <h2>{{recipe.title}}</h2>
      </router-link>
    </section>
  </main>
</template>

<script>
export default {
  name: 'List',
  data () {
    return {
        recipes: []
    }
  },
    mounted() {
      this.$http.get(`http://localhost:3000/api/recipes`)
          .then(response => {
              this.recipes = response.data
          }).catch(e => {
              console.error(e)
      })
    },
    methods: {
      addFavorite(id) {
          this.$http.post(`http://localhost:3000/api/favorites/${id}`)
              .then(response => {
                  console.log(response.data)
              }).catch(e => {
                  console.error(e)
          })
      }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .bg-home{
    display: flex;
    justify-content: center;
    align-items: center;
    background: url(../assets/img/bg.svg);
    background-size: cover;
    background-repeat: no-repeat;
    width: 100%;
    height: 390px;
  }

  h1{
    color: #54486E;
    font-size: 1.3rem;
    transform: translateY(-100px);
  }

  .section-middle{
    display: flex;
    justify-content: center;
  }

  .section-middle div{
    background-color: #fff;
    width: 50vw;
    margin: 50px 0;
    padding: 20px;
    border-radius: 5px;
  }
</style>
