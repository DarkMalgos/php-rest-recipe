import Vue from 'vue'
import Router from 'vue-router'
import List from '@/components/List'
import Add from '@/components/add'
import Recipe from '@/components/recipe'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'List',
            component: List
        },
        {
            path: '/add',
            name: 'add',
            component: Add
        },
        {
          path: '/recipe/:id',
          name: 'recipe',
          component: Recipe
        }
    ]
})
