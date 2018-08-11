<template>
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">List Items</div>
                <div class="card-body">
                    <!-- <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.nama_item }}</td>
                                <td>{{ item.stok }}</td>
                                <td v-if="item.stok == 0 && qtyInCart(item) == 0">
                                    <button class="btn btn-warning btn-sm btn-disable" disabled><i class="fa fa-shopping-cart"></i> Masukan</button>
                                </td>
                                <td v-else-if="item.stok == 0 && qtyInCart(item) != 0">
                                    <button class="btn btn-primary btn-sm" disabled><i class="fa fa-plus"></i></button>
                                    <button class="btn btn-primary btn-sm" @click="dec(item)"><i class="fa fa-minus"></i></button>
                                </td>
                                <td v-else>
                                    <button class="btn btn-warning btn-sm" @click="addToCart(item)" v-if="qtyInCart(item) == 0"><i class="fa fa-shopping-cart"></i> Masukan</button>
                                    <div v-else>
                                        <button class="btn btn-primary btn-sm" @click="inc(item)"><i class="fa fa-plus"></i></button>
                                        <button class="btn btn-primary btn-sm" @click="dec(item)"><i class="fa fa-minus"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                    <v-client-table 
                        :data="items" 
                        :columns="columns" 
                        :options="options">
                        <template slot="actions" scope="props">
                            <div v-if="props.row.stok == 0 && qtyInCart(props.row) == 0" style="text-align: center;">
                                <button class="btn btn-warning btn-sm btn-disable" disabled><i class="fa fa-shopping-cart"></i> Masukan</button>
                            </div>
                            <div v-else-if="props.row.stok == 0 && qtyInCart(props.row) != 0" style="text-align: center;">
                                <button class="btn btn-primary btn-sm" disabled><i class="fa fa-plus"></i></button>
                                <button class="btn btn-primary btn-sm" @click="dec(props.row)"><i class="fa fa-minus"></i></button>
                            </div>
                            <div v-else style="text-align: center;">
                                <button class="btn btn-warning btn-sm" @click="addToCart(props.row)" v-if="qtyInCart(props.row) == 0"><i class="fa fa-shopping-cart"></i> Masukan</button>
                                <div v-else>
                                    <button class="btn btn-primary btn-sm" @click="inc(props.row)"><i class="fa fa-plus"></i></button>
                                    <button class="btn btn-primary btn-sm" @click="dec(props.row)"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <div class="card-footer">
                    <a href="items/create" class="btn btn-danger float-right">Buat Item</a>
                </div>
            </div>
        </div>
        <div style="width: 20%;right: 0; height: 100%;position: fixed;top: 0;">
            <div class="card" style="height: 90%;border-radius: 0;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);border: none;">
                <div class="card-body"  style="overflow-y: auto;" id="tes">
                    <h5 class="text-center">Faktur List</h5>
                    <hr>
                    <div class="list-group">
                      <div class="list-group-item" v-for="c in cart" :key="c.id">
                        <div class="d-flex w-100 justify-content-between">
                          <h5 class="mb-1">{{ c.name }}</h5>
                          <small>Rp. {{ (c.qty*c.price).toLocaleString() }}</small>
                        </div>
                        <p class="mb-0" style="font-size: 10px">Ukuran : {{ c.ukuran }}</p>
                        <small>x{{ c.qty }}</small>
                      </div>
                      </div>
                </div>
                <div class="card-footer">
                    <span>Total : </span>
                    <span class="float-right">Rp. {{ total.toLocaleString() }}</span>
                </div>
            </div>
            <div style="height: 10%">
                <button class="btn btn-primary btn-block" style="position: absolute;bottom: 5%" data-toggle="modal" data-target="#modalFaktur" v-if="total > 0">Buat Faktur</button>
                <button class="btn btn-primary btn-block" style="position: absolute;bottom: 5%" v-else disabled>Buat Faktur</button>
            </div>
        </div>
        <div class="modal fade" id="modalFaktur" tabindex="-1" role="dialog" ref="myModalRef">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tujuan</label>
                            <input type="text" v-model="faktur.tujuan" class="form-control" placeholder="Ex : PT. Indoperkasa ..">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" v-model="faktur.alamat" rows="4"></textarea>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" @click="createFaktur" v-if="faktur.alamat != '' && faktur.tujuan != '' ">Buat</button>
                        <button type="button" class="btn btn-primary" disabled v-else>Buat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- <div class="col-md-6">
            <div class="card">
                <div class="card-header">Faktur</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Nama</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <tr v-for="c in cart" :key="c.id">
                                <td>{{ c.name }}</td>
                                <td>{{ c.qty }}</td>
                                <td>{{ c.price }}</td>
                                <td>{{ c.qty*c.price }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">Total</td>
                                <td>{{ total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalFaktur" v-if="total > 0">Buat Faktur</button>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import _ from 'lodash'
    import swal from 'sweetalert2'
    export default {
        mounted() {
            this.fetchData()
        },
        data() {
            return {
                items: [],
                item: {
                    nama_item:'',
                    harga_item: ''
                },
                cart: [],
                faktur: {
                    cart:[],
                    alamat: '',
                    tujuan:'',
                    total: 0,
                },
                columns: ['key','nama_item','ukuran','harga_item','stok', 'actions'],
                options: {
                    headings: {
                        key:'#',
                        nama_item: 'Nama',
                        ukuran: 'Ukuran',
                        harga_item: 'Harga',
                        stok: 'Stok',
                        actions: 'Actions'
                    },

                    templates: {
                        key : (h, row, i) => {
                            return i;
                        },

                        harga_item: (h, row) => {
                            return [
                                h('span',{
                                    domProps: {innerHTML: 'Rp. '},
                                    style: {float: 'left'}
                                }),
                                row.harga_item.toLocaleString(),
                            ];
                        }
                    },
                },
            }
        },

        computed: {
            total() {
                return _.sumBy(this.cart, (c) => {
                    return (c.price*c.qty)
                })
            },

        },

        methods: {

            fetchData() {
                axios.get('/api/items')
                    .then((res) => {
                        this.items = res.data
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },

            addToCart(item) {
                var found = _.find(this.items, ['id', item.id])
                if(typeof found == 'object') {
                    var index = _.indexOf(this.items, found)
                    this.items[index].stok--
                }
                this.cart.push({id:item.id, name:item.nama_item, qty:1, price:item.harga_item,ukuran: item.ukuran});
                this.toEnd();
            },

            toEnd() {
                var tes = this.$el.querySelector("#tes");
                tes.scrollTop = tes.scrollHeight+20;
            },

            inc(item) {
                var foundCart = _.find(this.cart, ['id', item.id])
                var foundItem = _.find(this.items, ['id', item.id])
                if(typeof foundCart == 'object') {
                    
                    var indexCart = _.indexOf(this.cart, foundCart)
                    var indexItem = _.indexOf(this.items, foundItem)
                    this.cart[indexCart].qty++
                    this.items[indexItem].stok--
                }
            },

            dec(item) {
                var foundCart = _.find(this.cart, ['id', item.id])
                var foundItem = _.find(this.items, ['id', item.id])
                if(typeof foundCart == 'object') {
                    
                    var indexCart = _.indexOf(this.cart, foundCart)
                    var indexItem = _.indexOf(this.items, foundItem)
                    if(this.cart[indexCart].qty == 1){
                        this.cart.splice(indexCart, 1);
                        this.items[indexItem].stok++
                    }
                    else if(this.cart[indexCart].qty > 1){
                        this.cart[indexCart].qty--
                        this.items[indexItem].stok++
                    }
                }
            },

            createFaktur() {
                this.faktur.cart = this.cart;
                this.faktur.total = this.total;
                $('#modalFaktur').modal('hide');
                
                axios.post('/faktur', this.faktur)
                .then((res) => {
                    // window.location.href = "faktur"
                    this.cart = [];
                    swal({
                      type: 'success',
                      title: 'No : '+res.data,
                      text: 'berhasil di buat!',
                      footer: '<a href="faktur">Cetak Faktur Disini</a>'
                    })
                })
                .catch((err) => {
                    swal({
                      type: 'error',
                      title: 'Error',
                      text: err
                    })
                })
            },
            qtyInCart(item) {
                var found = _.find(this.cart, ['id', item.id])
                if(typeof found == 'object') {
                    return found.qty;
                }else {
                    return 0;
                }
            },

            
        },
    }
</script>
<style>
    .fix {
        position: absolute;
    }
    .navbar {
        width: 80%;
    }
    .list-group-item {
        border: none;
        padding: 10px 0;
    }
    th {
        text-align: center;
    }
    td:nth-child(1), td:nth-child(3), td:nth-child(5){
        text-align: center;
    }
    td:nth-child(4){
        text-align: right;
    }
</style>
