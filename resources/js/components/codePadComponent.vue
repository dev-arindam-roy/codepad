<template>
    <div id="codePad">
        <div class="container" v-if="tabList.length > 0">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="nav-item" v-for="(item, index) in tabList" :key="item.id">
                                <a class="nav-link tab-link" v-bind:class="{ active: index == 0 }" data-toggle="tab" :href="`#${item.name}`" @click="showTabContent(item.id)">{{item.name}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="tab-content">
                        <div class="tab-pane container" v-for="(item, index) in tabList" :key="item.id" :id="`#${item.name}`" v-bind:class="{ active: index == 0 }">
                            <vue-editor v-model.trim="noteContent" :editorToolbar="customToolbar" class="note-editor"></vue-editor>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fix-btn-box" v-if="tabList.length > 0">
            <p><button type="button" class="btn btn-black" v-on:click="saveNote()"><i class="fas fa-save"></i></button></p>
            <p><button type="button" class="btn btn-black" v-on:click="deleteTab()"><i class="fas fa-trash-alt"></i></button></p>
            <!-- <p><button type="button" class="btn btn-black" v-on:click="downloadNote()"><i class="fas fa-file-pdf"></i></button></p> -->
            <input type="hidden" id="noteId" v-model="noteId">
            <input type="hidden" id="tabId" v-model="tabId">
        </div>
    </div>
</template>

<script>
import { VueEditor } from 'vue2-editor'
export default {
    name : 'code-pad',
    props:{
        
    },
    components: {
        VueEditor
    },
    data() {
        return {
            customToolbar: [
                [{'header': [false, 1, 2, 3, 4, 5, 6]}],
                ["bold", "italic", "underline"], 
                [
                    { list: "ordered" }, 
                    { list: "bullet" }
                ],
                [{ 'color': [] }, { 'background': [] }], 
                ['link', 'image', 'video'],
                ["code-block"]
            ],
            tabList: [],
            noteContent: '',
            noteId: 0,
            tabId: 0
        }
    },
    validations: {
        
    },
    methods: {
        async getAllTabsAndFirstNote() {
            var _this = this;
            _this.$root.isPageLoadingActive = true;
            axios.get('/getTabsAndNotes').then(response => {
                if (response.data.content.tabList.length == 0) {
                    _this.$router.push({name: 'create.tab'});
                } else {
                    _this.$root.isPageLoadingActive = false;
                    _this.tabList = response.data.content.tabList;
                    _this.tabId = response.data.content.tabList[0].id;
                }
                if (response.data.content.firstNote.id != undefined) {
                    _this.noteId = response.data.content.firstNote.id;
                    _this.noteContent = response.data.content.firstNote.note_content;
                }
                
            }).catch(function (error) {
                _this.$toast.error({
                    title:'System Error',
                    message:'Something went wrong!'
                });
            });
        },
        async showTabContent(_tabID) {
            var _this = this;
            _this.noteContent = '';
            _this.noteId = 0;
            if (_tabID != undefined && _tabID != 0 && _tabID != null) {
                _this.tabId = _tabID;
                _this.$root.isPageLoadingActive = true;
                var url = "/getNoteByTabId";
                const apiResponse = await axios({
                    method: 'post',
                    url: url,
                    data: {
                        tab_id: _this.tabId
                    },
                    headers: {'Content-Type': 'application/json'}
                })
                .then(function (response) {
                    _this.$root.isPageLoadingActive = false;
                    if (response.data.status == 200) {
                        if (response.data.type == 'success') {
                            _this.noteId = response.data.content.note.id;
                            _this.tabId = response.data.content.note.tab_id;
                            _this.noteContent = response.data.content.note.note_content;
                        }
                    }
                })
                .catch(function (error) {
                    _this.$root.isPageLoadingActive = false;
                    _this.$toast.error({
                        title:'System Error',
                        message:'Something went wrong!'
                    });
                });
            }
        },
        async saveNote() {
            var _this = this;
            _this.$root.isPageLoadingActive = true;
            var url = "/saveNote";
            const apiResponse = await axios({
                method: 'post',
                url: url,
                data: {
                    id: _this.noteId,
                    tab_id: _this.tabId,
                    note_content: _this.noteContent
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                _this.$root.isPageLoadingActive = false;
                if (response.data.status == 200) {
                    _this.noteId = response.data.content.note.id;
                    _this.tabId = response.data.content.note.tab_id;
                    if (response.data.type == 'success') {
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
                _this.$root.isPageLoadingActive = false;
                _this.$toast.error({
                    title:'System Error',
                    message:'Something went wrong!'
                });
            });
        },
        async deleteTab() {
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
                    _this.deleteTabProcess();
                }
            })
        },
        async deleteTabProcess() {
            var _this = this;
            var url = "/deleteTab";
            const apiResponse = await axios({
                method: 'post',
                url: url,
                data: {
                    tab_id: _this.tabId
                },
                headers: {'Content-Type': 'application/json'}
            })
            .then(function (response) {
                _this.$swal.close();
                if (response.data.status == 200) {
                    if (response.data.type == 'success') {
                        _this.$root.isPageLoadingActive = true;
                        let index = _this.tabList.indexOf(response.data.content.deletedTab);
                        _this.tabList.splice(index, 1);
                        _this.$toast.success({
                            title:'Success',
                            message:response.data.msg 
                        });
                        setTimeout(function() { 
                            window.location.href = "/";
                        }, 2000);
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
        async downloadNote() {

        }
    },
    mounted() {
        var _this = this;
        _this.getAllTabsAndFirstNote();
    }
}
</script>

<style scoped>

</style>