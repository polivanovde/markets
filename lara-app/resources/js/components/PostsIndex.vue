<template v-if="requestObj">
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createPost'}" class="btn btn-success">Create new post</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Posts list</div>
            <div class="panel-body">
                <posts-filter></posts-filter>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Автор</th>
                        <th>Описание</th>
                        <th width="100"><a href="#"
                                           class="btn btn-xs btn-danger"
                                           v-on:click="deleteEntries()">
                            Delete
                        </a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post in posts.data" :key="post.id">
                    <td>{{ post.title }}</td>
                    <td>{{ post.author.author }}</td>
                    <td><p v-html="post.description"></p></td>
                    <td>
                        <router-link :to="{name: 'editPost', params: {id: post.id}}" class="btn btn-xs btn-default">
                            Edit
                        </router-link>
                        <a href="#"
                           class="btn btn-xs btn-danger"
                           v-on:click="deleteEntry(post.id)">
                            Delete
                        </a>
                        <input type="checkbox" v-on:click="selectDeleteEntry(post.id)">
                    </td>
                    </tr>
                    </tbody>
                </table>
                <pagination :data="posts" @pagination-change-page="getAxios">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div>
        </div>
    </div>
</template>
<script>
import filterData from './PostsFilter.vue';
import pagination from 'laravel-vue-pagination';
import {each} from "lodash/collection";
export default {
    components: {
        'posts-filter': filterData,
        'pagination': pagination
    },
    data: function () {
        return {
            posts: {},
            selectToDelete: []
        }
    },
    mounted() {
        let app = this;
        this.$root.$on('filter', function(request, object) {
            app.getAxios(1, this.$route.query, request, object);
        });
        app.getAxios();
    },
    methods: {
        selectDeleteEntry(id) {
            if(this.selectToDelete.includes(id)){
                this.selectToDelete=_.without(this.selectToDelete,id)
            }else{
                this.selectToDelete.push(id)
            }
        },
        deleteEntries() {
            let app = this;
            $.each(this.selectToDelete, function(key, value) {
                let check = false;
                if(key === 0) {
                    check = true;
                }
                if(!app.deleteEntry(value, check)) return false;
            });
        },
        deleteEntry(id, needConfirm = true) {
            if (needConfirm) {
                if(!confirm("Вы действительно хотите удалить запись?")) return false;
            }
            let app = this;
            axios.delete('/posts/api/' + id)
                .then(function (resp) {
                    app.posts.data.splice(app.posts.data.findIndex(item => item.id === id), 1);
                })
                .catch(function (resp) {
                    alert("Не удалось удалить запись "+id);
                });

            return true;
        },
        getFilterArr(){
            let filterArr = this.$route.params.filter.split('/');
            let result = {};
            each(filterArr, function(value){
                let valueElem = value.split('-is-');
                result[valueElem[0]] = valueElem[1];
            });
            return result;
        },
        async getAxios(page, object = this.$route.query){
            let app = this;
            if(page) {
                object.page = page; //страница пагинации
                let getParams = (Object.keys(object).length === 0) ? '' : '?';
                let k=1;
                if(this.$route.query.page === 1) delete this.$route.query.page;
                each(this.$route.query, function(value, key) {
                    getParams += key+'='+value;
                    if (k < Object.keys(object).length) getParams += '&';
                    k++;
                });
                window.history.pushState('', '', getParams);
            }
            if(!object.limit) object.limit = 10;
            axios.get('/posts/api', {params: object})
                .then(function (resp) {
                    app.posts = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Не удалось загрузить записи");
                });
        }
    }
}
</script>
