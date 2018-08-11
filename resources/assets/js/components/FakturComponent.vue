<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Faktur</div>
                <div class="card-body">
                    <!-- <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No-Faktur</th>
                                <th>Total</th>
                                <th>Tujuan</th>
                                <th>Alamat</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(faktur, i) in fakturs" :key="i">
                                <td>{{ faktur.tanggal }}</td>
                            	<td>{{ faktur.no_faktur }}</td>
                            	<td>{{ faktur.total }}</td>
                                <td>{{ faktur.tujuan }}</td>
                            	<td>{{ faktur.alamat }}</td>
                            	<td>
                            		<a href="#" class="btn btn-success btn-sm" @click="print(faktur.id)">Cetak</a>
                            		<a href="#" class="btn btn-primary btn-sm" @click.prevent="getDetail(faktur)">Detail</a>
                            	</td>
                            </tr>
                        </tbody>
                    </table> -->
                    <v-client-table 
                        :data="fakturs" 
                        :columns="columns" 
                        :options="options">
                        <template slot="actions" scope="props">
                            <div class="text-center">
                                <button class="btn btn-sm" @click="batalEdit(props.row)" v-if="props.row.edit_mode == 1">Batal</button>
                                <button class="btn btn-primary btn-sm" @click="update(props.row)" v-if="props.row.edit_mode ==1">Simpan</button>
                                <a href="#" class="btn btn-success btn-sm" @click="print(props.row.id)"  v-if="props.row.edit_mode == 0"><i class="fa fa-print"></i></a>
                                <a href="#" class="btn btn-info btn-sm" @click.prevent="getDetail(props.row)"  v-if="props.row.edit_mode == 0"><i class="fa fa-info"></i></a>
                                <button class="btn btn-primary btn-sm" @click="editMode(props.row)" v-if="props.row.edit_mode == 0"><i class="fa fa-edit"></i></button>
                                <a href="#" class="btn btn-danger btn-sm" @click.prevent="remove(props.row)"  v-if="props.row.edit_mode == 0"><i class="fa fa-trash"></i></a>
                            </div> 
                        </template>
                    </v-client-table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalItem" tabindex="-1" role="dialog" ref="myModalRef">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Faktur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered text-center" id="tab">
	                        <thead>
	                            <tr>
	                                <th>Nama</th>
                                     <th>Ukuran</th>
	                            	<th>Qty</th>
	                            	<th>Harga</th>
	                            	<th>Subtotal</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            <tr v-for="(detail, i) in details" :key="i">
	                            	<td>{{ detail.nama_item }}</td>
                                    <td>{{ detail.ukuran }}</td>
	                            	<td>{{ detail.qty }}</td>
	                            	<td><span class="float-left">Rp.</span>{{ detail.harga_item.toLocaleString() }}</td>
	                            	<td><span class="float-left">Rp.</span>{{ detail.subtotal.toLocaleString() }}</td>
	                            </tr>
	                            <tr>
	                            	<td colspan="4">Total</td>
	                            	<td><span class="float-left">Rp.</span><span class="float-right">{{ total.toLocaleString() }}</span></td>
	                            </tr>
	                        </tbody>
	                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            	fakturs: [],
            	details: [],
                columns: ['tanggal','no_faktur','total','tujuan', 'alamat','actions'],
                options: {
                    headings: {
                        tanggal: 'Tanggal',
                        no_faktur: 'No.Faktur',
                        total: 'Total',
                        tujuan: 'Tujuan',
                        alamat: 'Alamat',
                        actions: 'Actions'
                    },

                    templates: {
                        total:(h, row) => {
                            return [
                                h('span',{
                                    domProps: {innerHTML: 'Rp. '},
                                    style: {float: 'left'}
                                }),
                                row.total.toLocaleString(),
                            ];
                        },
                        alamat:(h, row) => {
                            if(row.edit_mode == 0){
                                return h('small',row.alamat);
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.alamat
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.fakturs,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.fakturs, found);
                                                    this.fakturs[index].alamat = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },

                        tujuan:(h, row) => {
                            if(row.edit_mode == 0){
                                return row.tujuan;
                            }else{
                                return h('input',{
                                        domProps: {
                                            value: row.tujuan
                                        },
                                        on: {
                                            input : (e) => {
                                                this.value = e.target.value
                                                var found = _.find(this.fakturs,['id', row.id])
                                                if(typeof found == 'object') {
                                                    var index = _.indexOf(this.fakturs, found);
                                                    this.fakturs[index].tujuan = this.value;
                                                }
                                            }
                                        }
                                    });
                            }
                        },
                    },
                },

                editTes: [],
                
            }
        },

        computed: {
            total() {
                return _.sumBy(this.details, (c) => {
                    return (c.subtotal)
                })
            },

        },

        methods: {
        	fetchData() {
                axios.get('/api/faktur')
                    .then((res) => {
                        this.fakturs = res.data
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },

            getDetail(faktur) {
            	axios.get('faktur/'+faktur.id)
            	.then((res) => {
            		this.details = res.data
            		$('#modalItem').modal('show')
            	})
            	.catch((err) => {
            		console.log(err)
            	})
            },

            print(id) {
            	window.location.href = "faktur/cetak/"+id;
            },

            remove(faktur) {
                swal({
                  title: 'Anda yakin?',
                  text: "Anda yakin akan menghapus faktur "+faktur.no_faktur,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {
                    var found = _.find(this.fakturs,['id', faktur.id])
                    axios.delete("faktur/"+faktur.id)
                    .then((res)=> {
                        const fakturIndex = _.indexOf(this.fakturs, found);
                        this.fakturs.splice(fakturIndex, 1)
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

            editMode(faktur) {
                var found = _.find(this.fakturs,['id', faktur.id])

                this.editTes.push(
                    {
                        id: faktur.id ,
                        tujuan: faktur.tujuan,
                        alamat: faktur.alamat,
                        edit_mode: faktur.edit_mode
                    }
                );

                if(typeof found == 'object') {
                    var index = _.indexOf(this.fakturs, found);
                    this.fakturs[index].edit_mode = 1;
                }
            },

            batalEdit(faktur) {
                var found = _.find(this.fakturs,['id', faktur.id])
                var found2 = _.find(this.editTes,['id', faktur.id])
                if(typeof found == 'object') {
                    var index = _.indexOf(this.fakturs, found);
                    var index2 = _.indexOf(this.editTes, found2);
                    this.fakturs[index].tujuan = this.editTes[index2].tujuan;
                    this.fakturs[index].alamat = this.editTes[index2].alamat;
                    this.fakturs[index].edit_mode = 0;
                    this.editTes.splice(index2, 1);
                    console.log(this.editTes);
                }
            },

            update(faktur) {
                var found = _.find(this.fakturs,['id', faktur.id])
                axios.put("faktur/"+faktur.id, faktur)
                .then((res) =>{
                    if(typeof found == 'object') {
                        var index = _.indexOf(this.fakturs, found);
                        this.fakturs[index].edit_mode = 0;
                        swal(
                          'Sukses!',
                          'Berhasil Merubah',
                          'success'
                        )
                    }
                })
                .catch((err) => {
                    swal(
                      'Oppsss!',
                      err,
                      'error'
                    )
                })
            }
        }
    }
</script>
<style>
    th {
        text-align: center;
    }
    td{
        text-align: center;
    }
    td:nth-child(3){
        text-align: right;
    }
    td:nth-child(5){
        width: 25%;
    }
    .list-group-item {
        border: none;
        padding: 10px 0;
    }
    #tab th {
        text-align: center;
    }
    #tab td{
        text-align: center;
    }
    #tab td:nth-child(4), #tab td:nth-child(5){
        text-align: right;
    }
    /*
    .VueTables__child-row-toggler {
        width: 16px;
        height: 16px;
        line-height: 16px;
        display: block;
        margin: auto;
        text-align: center;
    }
     
    .VueTables__child-row-toggler--closed::before {
        content: "+";
    }
     
    .VueTables__child-row-toggler--open::before {
        content: "-";
    }*/
</style>
