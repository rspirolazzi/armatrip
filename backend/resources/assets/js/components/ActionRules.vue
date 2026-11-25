<template>
    <div>
        <label :for="name">
            <input :name="name" type="hidden" value="0"/>
            <input :name="name" :id="name" v-model="show" type="checkbox" value="1"/>
            Acciones
        </label>
        <div class="panel panel-info" v-show="show">
            <div class="panel-heading">Selección de opciones para agregar una acción</div>
            <div class="panel-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="action" class="">Si</label>
                        <select id="action" class="form-control " v-model="newItem.action"
                                @change.prevent="addItem">
                            <option v-for="action in listOfActions" :value="action">{{action.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product">de</label>
                        <select id="product" class="form-control" v-model="newItem.product" @change.prevent="addItem">
                            <optgroup v-for="item in listOfProducts" :label="item.category.name">
                                <option v-for="product in item.products" :value="product">{{product.name}}</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div v-if="items.length>0">
                    <hr/>
                    <p class="bg-info text-muted text-center">Reglas aplicadas</p>
                    <ul class="list-group">
                        <li v-for="(item, index) in items" class="list-group-item">
                            <span class="text-right">Se aplica esta acción cuando </span>
                            <b>
                                {{item.action.name}}
                                <input type="hidden" :name="name+'_action['+index+']'" :value="item.action.id"/>
                            </b>
                            <b>
                                {{item.product.name}}
                                <input type="hidden" :name="name+'_product['+index+']'" :value="item.product.id"/>
                            </b>
                            &nbsp;
                            <a href="#" alt="Eliminar esta accion" @click.prevent="removeItem(item)" class="pull-right">
                                &nbsp;<i class="fa fa-times"></i>
                            </a>
                            <span class="pull-right">
                                &nbsp;
                                <select v-model="item.order" :name="name+'_order['+index+']'" :value="item.order">
                                    <option v-for="order in getOrders" :value="order">{{order}}</option>
                                </select>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            name: String,
            checked: Boolean,
            listOfProducts: {type: Array, default: () => []},
            listOfActions: {type: Array, default: () => []},
            values: {type: Array, default: () => []},
        },
        data() {
            return {
                show: false,
                items: [],
                newItem: {action: {}, product: {}, order: 0},
                inputName: '',
                orders: [],
            }
        },
        computed: {
            isComplete() {
                return this._isComplete()
            },
            getOrders() {
                let options = [];
                for (let i = 1; i <= this.items.length; i++) {
                    options.push(i)
                }
                return options
            },
        },
        created() {
            if (this.values.length > 0) {
                this.items = this.values
            }
            this.show = this.checked
        },
        methods: {
            _isComplete() {
                return this.newItem.action.id && this.newItem.product.id;
            },
            addItem() {
                if (this._isComplete()) {
                    debugger
                    this.items.push({
                        action: this.newItem.action,
                        product: this.newItem.product,
                        order: this.newItem.order,
                    })
                    this.newItem = {
                        action: {},
                        product: {},
                        order: items.length + 1,
                    }
                }
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
