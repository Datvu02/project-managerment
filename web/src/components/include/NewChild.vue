<template>
  <div class="editable checklist-new-item u-gutter js-new-checklist-item editing" attr="newItem">
    <textarea-autosize
        class="edit field checklist-new-item-text js-new-checklist-item-input"
        placeholder="Thêm một mục"
        id="addChild"
        :min-height="30"
        @keydown.enter.native="addNewChild"
    />
    <div class="checklist-add-controls u-clearfix">
      <input
          class="nch-button nch-button--primary confirm mod-submit-edit js-add-checklist-item" type="submit"
          value="Thêm" @click="addNewChild"><a
        class="icon-lg icon-close dark-hover cancel js-cancel-checklist-item" @click="closeAddChild" href="#">
      <i class="el-icon-close"></i>
    </a>
      <div class="checklist-add-controls-spacer"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NewChild",
  methods:{
    closeAddChild(){
      this.$emit('closeAddChild')
    },
    addNewChild(){
      let data = {
        title: document.getElementById('addChild').value
      }
      this.$emit('addChild',data)
      document.getElementById('addChild').value = ''
    }
  },
}
</script>

<style scoped lang="scss">
.editable {
  cursor: auto;
  margin-left: 40px;

  button {
    margin-top: 8px;
  }

  textarea {
    background-color: #fff;
    box-shadow: inset 0 0 0 2px #0079bf;
    color: #172b4d;
    cursor: text;
    width: 100%;
    resize: vertical;
    float: none;
    display: block;
    z-index: 50;
    text-align: initial;
    text-decoration: none;
    min-height: 32px;
    margin: 4px 0 0;
    position: relative;
    outline: none;
    border: none;
    box-sizing: border-box;
    padding: 8px 12px;
    transition-property: background-color, border-color, box-shadow;
    transition-duration: 85ms;
    transition-timing-function: ease;
    line-height: 20px;
    margin-bottom: 10px;

  }
  .checklist-add-controls {

    input {
      float: left;
      margin: 0 4px 0 0;
      background-color: #5aac44;
      box-shadow: none;
      border: none;
      color: #fff;
    }

    input:hover {
      background-color: #61bd4f;
    }

    a {
      margin-left: 5px;
      color: #6b778c;
      font-size: 28px;
      font-weight: 600;
      cursor: pointer;
    }

    a:hover {
      color: #172b4d;
    }
  }

}
</style>