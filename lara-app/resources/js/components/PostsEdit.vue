<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexPosts'}" class="btn btn-default">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Edit post by {{ post.author.author }}</div>
            <div class="panel-body">
                <form v-on:submit="saveForm()">
                    <input type="hidden" v-model="post.author.email" class="form-control">
                    <div>
                        <div class="col-xs-12 form-group">
                            <label class="control-label">post title</label>
                            <input type="text" v-model="post.title" class="form-control">
                        </div>
                    </div>
                    <div>
                        <div class="col-xs-12 form-group">
                            <label class="control-label">post description</label>
                            <textarea v-model="post.description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div>
                        <div class="col-xs-12 form-group">
                            <button class="btn btn-success">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        let app = this;
        let id = app.$route.params.id;
        app.postId = id;
        axios.get('/posts/api/' + id)
            .then(function (resp) {
                app.post = resp.data;
            })
            .catch(function () {
                alert("Не удалось загрузить запись")
            });
    },
    data: function () {
        return {
            postId: null,
            post: {
                title: '',
                description: '',
                author: ''
            }
        }
    },
    methods: {
        saveForm() {
            event.preventDefault();
            var app = this;
            var newPost = app.post;
            newPost.author_email = this.post.author.email;
            axios.patch('/posts/api/' + app.postId, newPost)
                .then(function (resp) {
                    console.log(resp);
                    app.$router.go(-1);
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Не удалось изменить запись");
                });
        }
    }
}
</script>
