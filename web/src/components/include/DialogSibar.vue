<template>
<div class="window-sidebar">
    <div class="window-module u-clearfix">
        <h3 class="mod-no-top-margin js-sidebar-add-heading">Thêm vào thẻ</h3>
        <div class="u-clearfix">
            <a v-if="userAdmin" class="button-link js-edit-labels" ref="label" @click="showControl($event,'label')" title="Nhãn">
                <span class="icon-sm icon-label"><i class="el-icon-price-tag"></i></span>
                <span class="js-sidebar-action-text">Nhãn</span>
            </a>
            <a v-if="userAssign && (card.completion_status === 0 || card.completion_status === 3)" class="button-link js-add-checklist-menu" @click="showControl($event,'checkList')" title="Việc cần làm">
                <span class="icon-sm icon-checklist"><i class="el-icon-folder-checked"></i></span>
                <span class="js-sidebar-action-text">Việc cần làm</span>
            </a>
            <a v-if="userAdmin" class="button-link datetime-btn" href="#" title="Thời gian hết hạn" style="position: relative">
                <span class="icon-sm"> <i class="el-icon-alarm-clock"></i></span>
                <span class="js-sidebar-action-text">Thời gian hết hạn</span>
                <el-date-picker type="datetime" v-model="deadLine" @change="changeDeadline">
                </el-date-picker>
            </a>
            <a v-if="userAdmin" class="button-link btn-upload" href="#" title="Đính kèm">
                <input id="uploadFile" @change="uploadFile" class="uploadFile" type="file" />
                <span class="icon-sm icon-attachment"><i class="el-icon-paperclip"></i></span>
                <span class="js-sidebar-action-text">Đính kèm</span>
            </a>
        </div>
    </div>
    <div class="window-module u-clearfix">
        <h3 class="mod-no-top-margin">Thao tác</h3>
        <div class="u-clearfix">
            <hr>
            <a v-if="userAdmin" class="button-link js-delete-card remove-sidebar hide negate" href="#" @click="deleteCard" title="Xoá">
                <span class="icon-sm icon-remove"><i class="el-icon-minus"></i></span>
                <span class="js-sidebar-action-text">Xoá</span>
            </a>
            <a v-if="userAssign && (card.completion_status === 0 || card.completion_status === 3)" class="button-link js-delete-card remove-sidebar hide negate" href="#" @click="Submit" title="Nộp">
                <span class="icon-sm icon-remove"><i class="el-icon-minus"></i></span>
                <span class="js-sidebar-action-text">Nộp</span>
            </a>
            <a v-if="userAdmin && card.completion_status === 1" class="button-link js-delete-card remove-sidebar hide negate" href="#" @click="checkSubmitDone" title="Đã hoàn thành">
                <span class="icon-sm icon-remove"><i class="el-icon-minus"></i></span>
                <span class="js-sidebar-action-text">Xác nhận</span>
            </a>
            <a v-if="userAdmin && card.completion_status === 1" class="button-link js-delete-card remove-sidebar hide negate" href="#" @click="checkSubmitFailed" title="Chưa hoàn thành">
                <span class="icon-sm icon-remove"><i class="el-icon-minus"></i></span>
                <span class="js-sidebar-action-text">Chưa hoàn thành</span>
            </a>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment'
import api from '../../api'
export default {
    name: "DialogSibar",
    props: ['card', 'userAssign'],
    data() {
        return {
            deadLine: '',
            userAdmin: true,
            file: '',
        }
    },
    created: async function () {
        console.log(this.userAssign)
        const result = await api.getUserAdminByProject(this.$route.params.projectId);
        if (result.data.data === 1) {
            this.userAdmin = false
        }
    },
    methods: {
        uploadFile(e) {
            if (e.target.files.length) {
                this.file = e.target.files[0]
            }

            const formData = new FormData();
            if (this.file != null) {
                formData.append('file', this.file);
            }

            api.uploadFileCard(formData, this.card.id).then(() => {
                this.$emit('updateDetailCard')
            })

        },
        // checkUserAssign() {
        //     if (this.authUser.id === this.card.user_assign_id) {
        //         this.userAssign = true
        //     }
        // },
        changeDeadline() {
            let data = {
                deadline: this.formatDate(this.deadLine)
            }
            this.$emit('changeDeadline', data)
        },
        showControl(e, type) {
            let rect = e.target.getBoundingClientRect();
            let data = {
                left: rect.left,
                top: rect.top,
                type: type,
                id: this.card.id
            };
            this.$emit('showControl', data)
        },
        formatDate(dateString) {
            // Format từ dạng "02/24/2021 18:12:23" thành định dạng kiểu: "24/02/2021"
            return moment(dateString).format('YYYY-MM-DD HH:mm:ss')
        },
        deleteCard() {
            this.$emit('deleteCard', this.card.id)
        },
        
        Submit() {
            this.$emit('SubmitCard', this.card.id)
        },
        
        checkSubmitDone() {
            this.$emit('checkSubmitDone', this.card.id)
        },
        
        checkSubmitFailed() {
            this.$emit('checkSubmitFailed', this.card.id)
        }

    }
}
</script>

<style lang="scss" scoped>
@import "src/assets/scss/dialog_sibar";
</style>
