<template>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Key</th>
                <th>Nombre</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in items">
                <td class="col-md-2">{{item.key}}</td>
                <td class="col-md-3">{{item.label}}</td>
                <td class="col-md-7">
                    <div class="form-group" v-bind:class="{ 'has-warning': item.changed }">
                        <input :name="'values['+item.key+']'" class="form-control input-sm" type="text" v-model="item.value" @change="onChange" />
                        <!--<span v-if="item.changed" id="helpBlock2" class="help-block">El valor se modifico.</span>-->
                        <span class="help-block"><i class="fa fa-info-circle"></i> {{item.description}}</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            items: {type: Array, default: () => []},
        },
        data() {
            return {}
        },
        computed: {},
        created() {
            console.log('Settings', this.items)
        },
        methods: {

            onChange() {
                this.items = this.items.map((item) => ({
                    ...item,
                    changed: (item.value != item.old_value)
                }))
            },
            removeItem(value) {
                const _index = this.items.indexOf(value)
                this.items = this.items.filter((item, index) => {
                    return index !== _index
                })
            },
        },
        mounted() {
            console.log('Action Rules Component mounted.', this)
            //console.log('options.', JSON.parse(this.listOfProducts))
            console.log('checked', this.checked)
        }
    }
</script>
