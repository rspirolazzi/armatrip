<template>
    <div v-bind:class="className">
        <input
                autocomplete="off"
                type="text"
                :placeholder="placeholder"
                :name="name"
                v-model="query"
                v-on:keyup="autoComplete"
                class="form-control"/>
        <div v-if="results.length" class="autocomplete-container">
            <div class="text-center">
                <small class="text-muted">Url sugeridas de paquetes</small>
            </div>
            <ul class="list-group">
                <li class="list-group-item" v-for="result in results">
                    <a v-on:click.prevent="selectItem(result)"
                       href="#"
                       v-bind:class="(result.active ? '' : 'underline')" v-html="label(result)"/>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            className: {type: String, default: () => 'autocomplete'},
            name: String,
            placeholder: String,
            url: String,
            value: String,
            source: {type: Array, default: () => null},
            time: {type: Number, default: () => 3000},
            min: {type: Number, default: () => 2},
            maxResult: {type: Number, default: () => 10},
        },
        data() {
            return {
                query: '',
                results: [],
                typing: null,
                selectedItem: null,
                inputValue: '',
            }
        },
        created(){
          this.query = this.value
        },
        methods: {
            label(result) {
                const value = `${result.url} <small>(${result.name})</small>`
                const regexp = new RegExp('(' + this.query + ')', 'gi')
                return value.replace(regexp, '<b>$1</b>');
            },
            selectItem(item) {
                this.selectedItem = item
                this.results = []
                this.inputValue = this.selectedItem.url
                this.query = this.selectedItem.url
            },
            clearInput() {
                this.selectedItem = null
                this.results = []
                this.inputValue = ''
            },
            autoComplete() {
                this.results = [];
                if (this.source !== null) {
                    this._autoCompleteLocal()
                } else if (url !== null) {
                    this._autoCompleteRemote()
                }
            },
            _typingQuery(callback) {
                if (this.typing) {
                    clearTimeout(this.typing)
                }
                if (this.query.length > this.min) {
                    //this.typing = setTimeout(this.time, callback)
                    callback()
                }
            },
            _autoCompleteRemote() {
                this._typingQuery(() => {
                    /*fetch('/api/search', {params: {query: this.query}}).then(response => {
                        this.results = response.data;
                    });*/
                })
            },
            _autoCompleteLocal() {
                this._typingQuery(() => {
                    this.clearInput()
                    this.results = this.source.filter(x => (x['url'].toString().toLowerCase().indexOf(this.query.toLowerCase()) !== -1)
                        ||
                        (x['name'].toString().toLowerCase().indexOf(this.query.toLowerCase()) !== -1)
                    ).slice(0, this.maxResult)
                })
            },
        }
    }
</script>