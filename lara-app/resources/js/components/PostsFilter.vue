<template>
    <div>
        <select class="filter" name="title" :value="route.title ? decodeURIComponent(route.title) : ''" @change="onChange($event)">
            <option value="">Заголовок</option>
            <option v-for="filterData in uniqueItems('title')"
                    :key="filterData.title" :value="filterData.title"
                    :selected="route.title === filterData.title">
                {{ filterData.title }}
            </option>
        </select>
        <select class="filter" name="author_id" :value="route.title ? decodeURIComponent(route.author_id) : ''" @change="onChange($event)">
            <option value="">Автор</option>
            <option
                v-for="filterData in uniqueItems('author_id')"
                :key="filterData.author_id" :value="filterData.author_id"
                :selected="route.author_id == filterData.author_id">
                {{ filterData.author.author }}
            </option>
        </select>
        <select class="filter" name="limit" :value="route.limit ? route.limit : ''" @change="onChange($event)">
            <option value="">Показать</option>
            <option value="3">3</option>
            <option value="5">5</option>
            <option value="10">10</option>
        </select>
    </div>
</template>

<script>
import {each} from "lodash/collection";

export default {
    data: function () {
        return {
            filterData: [],
            select: '',
            route: this.$route.query
        }
    },
    computed: {
        uniqueItems() {
            return param => this.filterData.reduce((seed, current) => {
                let assignedObject = Object.assign(seed, {[current[param]]: current});
                return assignedObject;
            }, {});
        }
    },
    mounted() {
        var app = this;
        axios.get('/posts/api')
            .then(function (resp) {
                app.filterData = resp.data;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Не удалось загрузить записи");
            });
    },
    methods: {
        onChange(event) {
            var app = this;
            var filterName = event.target.name;
            var c = {};
            c[filterName] = event.target.value;
            app.requestObj = Object.assign(this.$route.query, c);
            if(event.target.value === "") delete app.requestObj[filterName];

            // генерируем событие
            this.$root.$emit('filter', app.requestObj);
        }
    }
}
</script>
