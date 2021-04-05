<template>
    <div id="createTab">
        <div class="container">
            <form @submit.prevent="createTabSubmit">
                <div class="row mt-5">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><strong>Tab Name:</strong></label>
                            <input type="text" v-model.trim="tabName" maxlength="30" class="form-control" placeholder="Tab Name">
                            <div class="text-danger" v-if="!$v.tabName.required && $v.tabName.$error">Please enter tab name.</div>
                            <div class="text-danger" v-if="!$v.tabName.minLength && $v.tabName.required && $v.tabName.$error">Please enter minimum 3 chars.</div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" v-model.trim="tabId">
                            <button type="submit" :disabled="isDisabled" class="btn btn-black"><i class="fas fa-save"></i> {{submitBtnTxt}}</button>
                            <button type="button" class="btn btn-black" v-show="isShowCancel" @click="cancelEdit()"><i class="far fa-window-close"></i> Cancel</button>
                            <router-link class="btn btn-black" v-show="tabList.total > 0 && !isShowCancel" :to="{name: 'root.index'}"><i class="fas fa-list"></i> My Tabs</router-link>
                        </div>
                    </div>
                    <div class="col-sm-8" v-if="tabList.total > 0">
                        <div class="row">
                            <div class="col-sm-8">
                                <label><strong>Total Tabs: {{tabList.total}}</strong></label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Search..." v-on:keyup="searchTab($event)">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Tab Name</th>
                                            <th style="width: 75px;">Order</th>
                                            <th style="width: 100px;">Last Entry</th>
                                            <th style="width: 100px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in tabList.data" :key="item.id">
                                            <td style="width: 50px;">{{index + 1}}</td>
                                            <td>{{item.name}}</td>
                                            <td style="width: 75px;">
                                                <input type="text" class="order-txt onlyNumber" maxlength="3" v-bind:value="item.order_no" v-on:blur="setTabOrder($event, item)">
                                            </td>
                                            <td style="width: 100px;">{{ lastNoteEntry[item.id] | moment("DD/MM/YY") }}</td>
                                            <td style="width: 100px;">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-black" @click="tabEdit(item)"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-black" @click="tabDelete(item)"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <pagination :data="tabList" @pagination-change-page="getAllTabs"></pagination>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <button type="button" class="btn btn-black" @click="setOrder()"><i class="fas fa-cog"></i> Set Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { required, minLength } from 'vuelidate/lib/validators'
export default {
    name: 'create-tab',
    components: {
        
    },
    data() {
        return {
            tabId: 0,
            tabName: '',
            tabList: {},
            $lastNoteEntry: [],
            pagination: this.$defaultPagination,
            isShowCancel: false,
            submitBtnTxt: 'Create',
            searchText: '',
            tabOrder: {}
        }
    },
    watch: {

    },
    computed: {
        isDisabled: function () {
            return this.tabName == ''
        }
    },
    validations: {
        tabName: {
            required,
            minLength: minLength(3)
        }
    },
    methods: {
        async getAllTabs(page = 1, searchText = this.searchText) {
            var _this = this;
            _this.$root.isPageLoadingActive = true;
            axios.get('/getAllTabs', {
                params: {
                    page: page,
                    name: searchText,
                    pagination: _this.pagination
                }
            }).then(response => {
                _this.$root.isPageLoadingActive = false;
                _this.tabList = response.data.content.tabList;
                _this.lastNoteEntry = response.data.content.lastEntry;
                _this.dataInit();
            }).catch(function (error) {
                _this.$toast.error({
                    title:'System Error',
                    message:'Something went wrong!'
                });
            });
        },
        dataInit() {
            this.tabName = '';
            this.tabId = 0;
            this.submitBtnTxt = 'Create';
            this.isShowCancel = false;
        },
        createTabSubmit() {
            var _this = this;
            _this.$v.$touch();
            if (!_this.$v.$error) {
                _this.createTabProcess();
            }
        },
        async createTabProcess() {
            var _this = this;
            _this.$root.isPageLoadingActive = true;
            var url = "/saveTab";
            const apiResponse = await axios({
                method: 'post',
                url: url,
                data: {
                    id: _this.tabId,
                    name: _this.tabName
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                if (response.data.status == 200) {
                    _this.$root.isPageLoadingActive = false;
                    if (response.data.type == 'success') {
                        _this.dataInit();
                        _this.$v.$reset();
                        _this.$toast.success({
                            title:'Success',
                            message:response.data.msg 
                        });
                        _this.getAllTabs();
                    }
                    if (response.data.type == 'error') {
                        _this.$root.isPageLoadingActive = false;
                        _this.$toast.error({
                            title:'Error',
                            message:response.data.msg 
                        });
                    }
                }
            })
            .catch(function (error) {
                _this.$toast.error({
                    title:'System Error',
                    message:'Something went wrong!'
                });
            });
        },
        tabEdit(tab) {
            var _this = this;
            _this.tabId =  tab.id;
            _this.tabName = tab.name;
            _this.submitBtnTxt = 'Save';
            _this.isShowCancel = true;
        },
        cancelEdit() {
            this.$v.$reset();
            this.dataInit();
        },
        tabDelete(tab) {
            var _this = this;
            _this.$swal({
                title: 'Are you sure?',
                text: "You want to delete this tab?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((actionResult) => {
                if (actionResult.value) {
                    _this.$swal({
                        title: 'Please Wait ...',
                        willOpen () {
                            _this.$swal.showLoading()
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                    _this.deleteTabProcess(tab);
                }
            })
        },
        async deleteTabProcess(tab) {
            var _this = this;
            var url = "/deleteTab";
            const apiResponse = await axios({
                method: 'post',
                url: url,
                data: {
                    tab_id: tab.id
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                _this.$swal.close();
                if (response.data.status == 200) {
                    if (response.data.type == 'success') {
                        let index = _this.tabList.data.indexOf(tab);
                        _this.tabList.data.splice(index, 1);
                        _this.tabList.total--;
                        _this.$toast.success({
                            title:'Success',
                            message:response.data.msg 
                        });
                    }
                    if (response.data.type == 'error') {
                        _this.$toast.error({
                            title:'Error',
                            message:response.data.msg 
                        });
                    }
                }
            })
            .catch(function (error) {
                _this.$swal.close();
                _this.$toast.error({
                    title:'System Error',
                    message:'Something went wrong!'
                });
            });
        },
        async searchTab(event) {
            var _this = this;
            if (event.keyCode === 13 || event.which === 13) {
                _this.searchText = event.target.value;
                _this.getAllTabs(1, _this.searchText);
            } 
            if (event.target.value.length == 0) {
                _this.searchText = '';
                _this.getAllTabs();        
            }
        },
        showNote(tab) {
            this.$router.push({ name: 'root.index', query: { tab: tab.id } });
        },
        setTabOrder(event, itm) {
            this.tabOrder[itm.id] = event.target.value;
        },
        async setOrder() {
            var _this = this;
            if (Object.keys(_this.tabOrder).length) {
                _this.$root.isPageLoadingActive = true;
                var url = "/setTabOrder";
                const apiResponse = await axios({
                    method: 'post',
                    url: url,
                    data: {
                        tab_order: _this.tabOrder
                    },
                    headers: {'Content-Type': 'application/json'}
                })
                .then(function (response) {
                    if (response.data.status == 200) {
                        _this.$root.isPageLoadingActive = false;
                        if (response.data.type == 'success') {
                            _this.$toast.success({
                                title:'Success',
                                message:response.data.msg 
                            });
                            _this.getAllTabs();
                        }
                        if (response.data.type == 'error') {
                            _this.$root.isPageLoadingActive = false;
                            _this.$toast.error({
                                title:'Error',
                                message:response.data.msg 
                            });
                        }
                    }
                })
                .catch(function (error) {
                    _this.$toast.error({
                        title:'System Error',
                        message:'Something went wrong!'
                    });
                });
            }
        }
    },
    mounted() {
        var _this = this;
        _this.getAllTabs();
    }
}
</script>
