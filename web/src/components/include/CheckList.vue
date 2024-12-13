<template>
  <div class="checklist">
    <div class="window-module-title"><span
        class="window-module-title-icon icon-lg icon-checklist"><i
        class="el-icon-folder-checked"></i></span>
      <div class="editable" attr="name">
        <h3 class="current" dir="auto" v-if="!showEditCheckList" @click="showEditCheckList = !showEditCheckList">
          {{ checkList.title }}</h3>
        <div class="window-module-title-options" v-if="!showEditCheckList">
          <a class="nch-button confirm-delete" @click="deleteChecklist"
             href="#" style="margin: 0">Xóa</a>
        </div>
        <div class="edit edit-heavy" v-if="showEditCheckList">
          <textarea-autosize
              :value="checkListTitle"
              class="description-draft"
              placeholder="Thêm mô tả chi tiết hơn..."
              ref="checklistCard"
              id="checklistCard"
              :min-height="30"
              @keydown.enter.native="updateCheckListTitle"
          />
          <div class="checklist-title-edit-controls u-clearfix"><input
              class="nch-button nch-button--primary confirm mod-submit-edit js-save-checklist-title"
              type="submit" value="Lưu" @click="updateCheckListTitle">
            <a class="icon-lg icon-close dark-hover cancel js-cancel-checklist-title" @click="closeShowEditCheckList">
              <i class="el-icon-close"></i>
            </a></div>
        </div>
      </div>
    </div>
    <div class="checklist-progress">
      <el-progress :percentage="process"></el-progress>
    </div>
    <div class="checklist-items-list js-checklist-items-list js-no-higher-edits ui-sortable">
      <CheckListChild v-for="(item,index) in checkList.check_list_childs" @updateCheckList="updateCheckList" @changeStatusChild="changeStatusChild"
                      :child="item" :key="index"/>
    </div>
    <div v-if="!addChild" class="editable checklist-new-item u-gutter js-new-checklist-item" attr="newItem">
      <button class="new-checklist-item-button nch-button mt-4 hide-on-edit" @click="addChild = true">Thêm một mục
      </button>
    </div>
    <NewChild @addChild="addNewChild" @closeAddChild="closeAddChild" v-if="addChild"/>
  </div>
</template>

<script>

import CheckListChild from "@/components/include/CheckListChild";
import NewChild from "@/components/include/NewChild";
import api from "../../api"

export default {
  name: "CheckList",
  props: ['checkList', 'card'],
  components: {
    CheckListChild,
    NewChild
  },
  methods: {
    closeAddChild() {
      this.addChild = false;
    },
    updateCheckListTitle() {
      let data = {
        title: document.getElementById('checklistCard').value
      }
      api.updateCheckListTitle(data, this.checkList.id).then(() => {
        this.showEditCheckList = false
        this.$emit('updateCheckList')
      })
    },
    deleteChecklist(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        data: this.checkList
      }
      this.$emit('openDeleteCheckList',data)
    },
    closeShowEditCheckList() {
      this.showEditCheckList = false
    },
    loadTitle() {
      this.checkListTitle = this.checkList.title
    },
    addNewChild(data) {
      data.check_list_id = this.checkList.id
      api.addCheckListChild(data).then(() => {
        this.$emit('updateCheckList')
        this.closeAddChild()
      })
    },
    changeStatusChild(data) {
      api.changeStatusChild(data.data, data.id).then(() => {
        this.$emit('updateCheckList')
      })
    },
    updateCheckList(){
      this.$emit('updateCheckList')
    }
  },
  data() {
    return {
      showEditCheckList: false,
      addChild: false,
      checkListTitle: '',
    }
  },
  computed:{
    process: function (){
      let childs = this.checkList.check_list_childs
      let success = 0;

      if (childs.length === 0 ) return  0;

      childs.forEach((item) => {
        if (item.status === 1) success++
      })


      return Math.ceil(100 * (success / childs.length))
    }
  },
  mounted() {
    this.loadTitle()
  },
  updated() {
    this.loadTitle()
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/check_list";
</style>