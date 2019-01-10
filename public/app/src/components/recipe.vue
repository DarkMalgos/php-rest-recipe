<template>
    <section>
        <h1>{{recipe.recipe.title}} <span></span></h1>

        <h2 class="mb-10">ingrédients</h2>
        <div>
            <p v-for="ingredient in recipe.ingredients">{{ ingredient.name }} {{ingredient.quantity}}</p>
        </div>

        <h2 class="mt-20 mb-10">Étapes</h2>
        <div>
            <div v-for="step in recipe.steps" class="step">
                <h3>étape {{step.step}}</h3>
                <p>{{step.text}}</p>
            </div>
        </div>

        <h2 class="mt-20 mb-10">commentaires</h2>
        <div>
            <div v-for="comment in recipe.comments" class="comment">
                <h3>{{comment.name}}</h3>
                <p>{{comment.comment}}</p>
            </div>
        </div>
        <form action="" @submit.prevent="addComment" class="form mt-20">
            <label for="">Nom</label>
            <input type="text" v-model="comment.name">
            <label for="">commentaire</label>
            <textarea cols="30" rows="10" v-model="comment.comment"></textarea>
            <input type="submit">
        </form>
    </section>
</template>

<script>
export default {
  name: 'recipe',
  data () {
    return {
        recipe: '',
        id: '',
        comment: {
            name: '',
            comment: '',
            id_recipe: ''
        }
    }
  },
    mounted() {
      this.init()
    },
    methods: {
      init() {
           this.id = window.location.pathname.split('/')[2]
          this.comment.id_recipe = this.id
          this.$http.get(`http://localhost:3000/api/recipes/${this.id}`)
              .then(response => {
                  this.recipe = response.data
              }).catch(e => {
              console.error(e)
          })
      },
      addComment(){
          this.$http.post(`http://localhost:3000/api/comments/`, {
              comment: this.comment
          })
              .then(response => {
                  this.init()
              }).catch(e => {
                  console.error(e)
          })
      }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    section {
        width: 80vw;
        margin: 0 auto;
        margin-top: 5vh;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
    }
    h1 {
        align-self: center;
    }
    .step {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: auto;
    }
    .step h3 {
        margin-right: 20px;
    }
    .mt-20 {
        margin-top: 20px;
    }
    .mb-10 {
        margin-bottom: 10px;
    }
    .comment {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .comment h3 {
        margin-bottom: 10px;
    }
    .form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 45vw;
        background-color: #f0f0f2;
        border-radius: 5px;
        padding: 30px;
    }
    input {
        height: 20px;
        border-radius: 5px;
        border: none;
        padding: 5px;
        background-color: white;
        margin: 5px;
    }
    textarea {
        border-radius: 5px;
        border: none;
        padding: 5px;
        background-color: white;
        resize: none;
    }
    .form input[type='submit'] {
        align-self: flex-end;
        background-color: #200f7f;
        border: none;
        width: 80px;
        padding: 5px;
        border-radius: 5px;
        color: white;
    }
</style>
