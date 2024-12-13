<template>
  <div class="checklist-item no-assignee no-due">
    <div class="checklist-item-checkbox enabled js-toggle-checklist-item"
         data-test-id="checklist-item-checkbox">
      <el-checkbox v-model="status" @change="changeStatus"></el-checkbox>
    </div>
    <div class="checklist-item-details  editable" attr="name">
      <div class="checklist-item-row">
        <div class="checklist-item-text-and-controls" v-if="!showEditCheckListChild"
             @click="showEditCheckListChild = !showEditCheckListChild">
          <span v-if="this.status == 0" class="checklist-item-details-text">{{ child.title }}</span>
          <span v-if="this.status == 1" class="checklist-item-details-text"><strike>{{ child.title }}</strike></span>
          <div class="checklist-item-controls">
            <div class="checklist-item-delete" data-elevation="1">
              <div class="delete-item">
                <a class="delete-child" @click="deleteChild" data-test-id="checklist-item-overflow-menu-button">
                  <span class="icon-sm icon-overflow-menu-horizontal"><i class="el-icon-close"></i></span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="edit check-item-options-menu" v-if="showEditCheckListChild">
          <textarea-autosize
              :value="child.title"
              placeholder="Thêm mô tả chi tiết hơn..."
              id="checklistChildCard"
              :min-height="30"
              @keydown.enter.native="updateCheckListChildTitle"
          />
          <p class="edits-error error"></p>
          <div class="edit-controls u-clearfix"><input
              class="nch-button nch-button--primary confirm mod-submit-edit js-save-edit" @click="updateCheckListChildTitle" type="submit" value="Lưu">
            <a class="icon-lg icon-close dark-hover cancel js-cancel-edit" href="#" @click="closeEdit"><i class="el-icon-close"></i></a>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import api from "@/api";

export default {
  name: "CheckListChild",
  props: ['child'],
  methods: {
    loadStatus() {
      this.status = this.child.status == 1 ? true : false
    },
    changeStatus() {
      let payload = {
        id: this.child.id,
        data: {
          status: this.status ? 1 : 0
        }
      }
      this.$emit('changeStatusChild', payload);
    },
    updateCheckListChildTitle() {
      let data = {
        title: document.getElementById('checklistChildCard').value
      }

      console.log(data);

      api.updateCheckListTitleChild(data, this.child.id).then(() => {
        this.showEditCheckListChild = false
        this.$emit('updateCheckList')
        this.closeEdit()
      })
    },
    deleteChild(e){
      e.stopPropagation()
      api.deleteChecklistChild( this.child.id).then(() => {
        this.$emit('updateCheckList')
      })
    },
    closeEdit(){
      this.showEditCheckListChild = false
    }
  },
  data() {
    return {
      showEditCheckListChild: false,
      status: 0
    }
  },
  mounted() {
    this.loadStatus()
  },
  updated() {
    this.loadStatus()
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/child_check_list";
</style>