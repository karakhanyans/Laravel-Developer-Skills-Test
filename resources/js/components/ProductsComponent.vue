<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mt-5 border-bottom">Create Product</h3>
                <form>
                    <div class="form-group">
                        <label class="control-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" placeholder="Product name"
                               v-model="newProduct.name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Quantity in stock</label>
                        <input type="number" class="form-control" id="quantityStock" placeholder="Quantity in stock"
                               v-model="newProduct.quantity">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Price per item</label>
                        <input type="number" class="form-control" id="priceItem" placeholder="Price per item"
                               v-model="newProduct.price">
                    </div>
                    <button type="submit" class="btn btn-primary" @click.prevent="addProduct">Add product</button>
                </form>
            </div>
            <div class="col-md-12 mt-5">
                <h3 class="mt-5">Products</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Entire price</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in products" :key="index">
                        <th scope="row">{{ ++index }}</th>
                        <template v-if="item.edit">
                            <td>
                                <input type="text" class="form-control" v-model="item.name">
                            </td>
                            <td>
                                <input type="text" class="form-control" v-model="item.price">
                            </td>
                            <td>
                                <input type="text" class="form-control" v-model="item.quantity">
                            </td>
                        </template>
                        <template v-else>
                            <td>{{ item.name }}</td>
                            <td>${{ item.price }}</td>
                            <td>{{ item.quantity }}</td>
                        </template>
                        <td>${{ item.price*item.quantity }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" v-if="item.edit" @click="editProduct(--index)">
                                Save
                            </button>
                            <button class="btn btn-sm btn-primary" v-else @click="item.edit = true">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger" @click="removeProduct(--index)">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>${{ totalSum }}</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: true,
                newProduct: {
                    name: '',
                    quantity: 0,
                    price: 0,
                    edit: false
                },
                products: [],
            }
        },
        created() {
            this.getProducts();
        },
        computed: {
            totalSum() {
                if(!this.loading) {
                    return this.products.reduce(function (totalSum, product) {
                        return totalSum + (product.price * product.quantity);
                    }, 0);
                }
            }
        },
        methods: {
            getProducts() {
                this.$http.get('/get-products').then(response => {
                    this.products = response.data.data;
                    this.loading = false;
                })
            },
            addProduct() {
                this.products.push(this.newProduct);
                this.$http.post('/add-product', this.newProduct).then(response => {
                }, () => {
                    alert('Server Error!');
                });
                this.newProduct = {}
            },
            editProduct(index) {
                let product = this.products[index];
                product.edit = false;
                this.$http.put('/edit-product/' + index, product).then(response => {
                }, () => {
                    alert('Server Error!');
                })
            },
            removeProduct(index) {
                this.products.splice(index, 1);
                this.$http.delete('/delete-product/' + index).then(response => {
                }, () => {
                    alert('Server Error!');
                })
            }
        }
    }
</script>
