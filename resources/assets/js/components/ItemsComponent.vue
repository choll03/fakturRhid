<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List Items</div>
                <div class="card-body">
                    <!-- <table class="table text-center">
                        <thead>
                            <tr>
                            	<th>No</th>
                                <th>Nama</th>
                                <th>Ukuran</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in items" :key="i">
                            	<td>{{ i+1 }}</td>
                                <td>
                                	<input type="text" v-if="item.edit_mode == 1" v-model="item.nama_item">
                                	<span v-else>{{ item.nama_item }}</span>
                            	</td>
                                <td>
                                    <input type="text" v-if="item.edit_mode == 1" v-model="item.ukuran">
                                    <span v-else>{{ item.ukuran }}</span>
                                </td>
                                <td>
                                	<input type="text" v-if="item.edit_mode == 1" v-model="item.harga_item">
                                	<span v-else>{{ item.harga_item }}</span>
                            	</td>
                                <td>
                                	<input type="text" v-if="item.edit_mode == 1" v-model="item.stok">
                                	<span v-else>{{ item.stok }}</span>
                            	</td>
                                <td>
                                	<button class="btn btn-primary btn-sm" @click="editMode(item)" v-if="item.edit_mode == 0"><i class="fa fa-edit"></i></button>
                                	<button class="btn btn-sm" @click="batalEdit(item)" v-if="item.edit_mode == 1">Batal</button>
                                	<button class="btn btn-primary btn-sm" @click="update(item)" v-if="item.edit_mode == 1">Simpan</button>
                                	<a href="#" class="btn btn-danger btn-sm" @click.prevent="remove(item)"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                    <v-client-table 
                        :data="items" 
                        :columns="columns" 
                        :options="options">
                        <template slot="actions" scope="props">
                            <div class="text-center">
                                <button class="btn btn-primary btn-sm" @click="editMode(props.row)" v-if="props.row.edit_mode == 0"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm" @click="batalEdit(props.row)" v-if="props.row.edit_mode == 1">Batal</button>
                                <button class="btn btn-primary btn-sm" @click="update(props.row)" v-if="props.row.edit_mode == 1">Simpan</button>
                                <a href="#" class="btn btn-danger btn-sm" @click.prevent="remove(props.row)" v-if="props.row.edit_mode == 0"><i class="fa fa-trash"></i></a>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <div class="card-footer">
                	<button class="btn btn-success float-right" data-toggle="modal" data-target="#modalItem">Tambah</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalItem" tabindex="-1" role="dialog" ref="myModalRef">
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
                            <label>Nama</label>
                            <input type="text" class="form-control" v-model="item.nama_item">
                        </div>
                        <div class="form-group">
                            <label>Ukuran</label>
                            <input type="text" class="form-control" v-model="item.ukuran">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" class="form-control" v-model="item.harga_item">
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="text" class="form-control" v-model="item.stok">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" @click="create">Buat</button>
                    </div>
                </div>
            </div>
        </div>
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
                    ukuran: '',
                    harga_item: '',
                    stok:''
                },
                editTes: [],
                columns: ['key','nama_item','ukuran','harga_item','stok', 'actions'],
                options: {
                    headings: {
                        key: '#',
                        nama_item: 'Nama',
                        ukuran: 'Ukuran',
                        harga_item: 'Harga',
                        stok: 'Stok',
                        actions: 'Actions'
                    },

                    templates: {
                        key : (h, row, i) => { return i },
                        harga_item: (h, row) => {
                            if(row.edit_mode == 0){
                                return [
                                    h('span',{
                                        domProps: {innerHTML: 'Rp. '},
                                        style: {float: 'left'}
                                    }),
                                    row.harga_item.toLocaleString(),
                                ];
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.harga_item
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.items,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.items, found);
                                                    this.items[index].harga_item = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },
                        nama_item:(h, row) => {
                            if(row.edit_mode == 0){
                                return row.nama_item;
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.nama_item
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.items,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.items, found);
                                                    this.items[index].nama_item = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },
                        ukuran:(h, row) => {
                            if(row.edit_mode == 0){
                                return row.ukuran;
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.ukuran
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.items,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.items, found);
                                                    this.items[index].ukuran = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },
                        stok:(h, row) => {
                            if(row.edit_mode == 0){
                                return row.stok;
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.stok
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.items,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.items, found);
                                                    this.items[index].stok = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },
                    }
                },
            }
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
            create() {
                axios.post('/api/items', this.item)
                .then((res)=>{
                    this.items.unshift(res.data)
                    this.item.nama_item = ''
                    this.item.ukuran = ''
                    this.item.harga_item = ''
                    this.item.stok = ''
                    $('#modalItem').modal('hide');
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            remove(item) {
                swal({
                  title: 'Anda yakin?',
                  text: "Anda akan menghapus permanent Item ini",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {
                    var found = _.find(this.items,['id', item.id])
                    axios.delete("delete/"+item.id)
                    .then((res)=> {
                        const itemIndex = _.indexOf(this.items, found);
                        this.items.splice(itemIndex, 1)
                        swal(
                          'Terhapus!',
                          'Item berhasil terhapus',
                          'success'
                        )
                    })
                    .catch((err) => {
                        swal(
                          'Oppsss!',
                          err,
                          'error'
                        )
                    })
                  }
                })
                
            },

            editMode(item) {
            	var found = _.find(this.items,['id', item.id])
                this.editTes.push({
                    id: item.id,
                	edit_mode: item.edit_mode,
                	nama_item: item.nama_item,
                    ukuran: item.ukuran,
                	harga_item: item.harga_item,
                	stok: item.stok,
                })

            	if(typeof found == 'object') {
            		var index = _.indexOf(this.items, found);
            		this.items[index].edit_mode = 1;
            	}
            },

            batalEdit(item) {
            	var found = _.find(this.items,['id', item.id])
                var found2 = _.find(this.editTes,['id', item.id])
            	if(typeof found == 'object') {
            		var index = _.indexOf(this.items, found);
                    var index2 = _.indexOf(this.editTes, found2);
            		this.items[index].nama_item = this.editTes[index2].nama_item;
                    this.items[index].ukuran = this.editTes[index2].ukuran;
            		this.items[index].harga_item = this.editTes[index2].harga_item;
            		this.items[index].stok = this.editTes[index2].stok;
            		this.items[index].edit_mode = 0;
            	}
            },

            update(item) {
            	var found = _.find(this.items,['id', item.id])
            	axios.put("update/"+item.id, item)
            	.then((res) =>{
                   console.log(res.data)
            		if(typeof found == 'object') {
	            		var index = _.indexOf(this.items, found);
	            		this.items[index].edit_mode = 0;
	            	}
            	})
            	.catch((err) => {
            		console.log(err)
            	})
            }
        }
    }
</script>
<style>
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
