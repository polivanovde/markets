<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexPosts'}" class="btn btn-default">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Create new post</div>
            <div class="panel-body">
                <form v-on:submit="saveForm()">
                    <div>
                        <div class="col-xs-12 form-group">
                            <label class="control-label">post title</label>
                            <input type="text" v-model="post.title" class="form-control">
                        </div>
                    </div>
                    <div>
                        <div class="col-xs-12 form-group">
                            <label class="control-label">author_email</label>
                            <input type="email" v-model="post.author_email" class="form-control">
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
                            <button class="btn btn-success">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            post: {
                title: '',
                author_email: '',
                description: '',
            }
        }
    },
    methods: {
        saveForm() {
            event.preventDefault();
            var app = this;
            var newPost = app.post;
            axios.post('/posts/api', newPost)
                .then(function (resp) {
                    app.$router.go(-1);
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Не удалось создать запись");
                });
        }
    }
}
</script>
