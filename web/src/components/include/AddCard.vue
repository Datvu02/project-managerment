<template>
    <div>
      <el-dialog :visible.sync="visible" width="40%">
        <div class="window-wrapper js-tab-parent" data-elevation="1">
          <a class="icon-md icon-close close-button js-close-window" @click="closeModal"><i class="iconColse el-icon-close"></i></a>
          <div class="card-detail-window u-clearfix">
            <div class="window-header">
              <span class="window-header-icon"><i class="iconBank el-icon-bank-card"></i></span>
              <div class="window-title">
                <textarea @keydown.enter="updateCardTitle" :value="cardTitle"></textarea>
              </div>
            </div>
            <div class="window-content">
              <div class="window-main-col">
                <div class="card-detail-data u-gutter">
                  <div class="editable" attr="desc">
                    <div class="description-content js-desc-content">
                      <div class="current markeddown hide-on-edit js-desc js-show-with-desc hide" dir="auto"></div>
                      <p v-if="!editDescriptionModal && cardDetail.description==null" @click="openEditDescription" class="u-bottom js-hide-with-desc"></p>
                    </div>
                  </div>
                </div>
                <div class="window-module">
                  <div>
                    <h3 class="u-inline-block"><i class="el-icon-user" style="font-size: 25px; margin-right: 20px;"></i>Phân công</h3>
                    <div class="user_asign" style="display: flex">
                    </div>
                  </div>
                  <div class="window-module">
                    <div>
                      <h3 class="u-inline-block"><i class="el-icon-alarm-clock" style="font-size: 25px; margin-right: 20px;"></i>Tạo thời gian hoàn thành công việc</h3>
                      <div class="datetime-btn" href="#" title="Thời gian hết hạn" style="position: relative">
                        <span class="icon-sm"> <i class="el-icon-alarm-clock"></i></span>
                        <span class="js-sidebar-action-text">Thời gian hết hạn</span>
                        <el-date-picker type="datetime" v-model="deadLine" @change="changeDeadline">
                        </el-date-picker>
                      </div>
                    </div>
                  </div>
                  <div class="window-module">
                    <div>
                      <h3 class="u-inline-block"><i class="el-icon-paperclip" style="font-size: 25px; margin-right: 20px;"></i>Import file đính kèm mô tả công việc</h3>
                      <input id="uploadFile" @change="uploadFile" class="uploadFile" type="file" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </el-dialog>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      visible: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        cardTitle: '',
        deadLine: '',
        editDescriptionModal: false,
        cardDetail: {}
      }
    },
    methods: {
      updateCardTitle() {
        // cập nhật tên thẻ
      },
      openEditDescription() {
        // mở mô tả chi tiết công việc
      },
      changeDeadline() {
        // cập nhật thời gian hoàn thành công việc
      },
      uploadFile() {
        // upload file đính kèm mô tả công việc
      },
      closeModal() {
        this.visible = false
      }
    }
  }
  </script>