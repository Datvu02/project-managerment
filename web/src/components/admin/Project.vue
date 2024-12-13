<template>
  <AdminLayout>
    <template #main>
      <el-row :gutter="12">
        <el-col :span="8" style="padding: 20px; cursor: pointer" v-for="(project) in projects" :key="project.id">
          <el-card shadow="always">
            <div class="aProject" style="width: 100%; height: 100%;" @click="detailProject(project.id)">
              <h4>{{project.name}}</h4>
              <span v-if="project.description.length > 0">
              {{project.description}}
            </span>
              <div class="action" style="text-align: right; padding: 10px 0 0 0;">
                <el-button type="warning" icon="el-icon-s-data"
                           @click="analytic(project, $event)" circle></el-button>
                <el-button type="danger" icon="el-icon-delete"
                           v-if="project.user_id === authUser.id" @click="destroy(project.id, $event)" circle></el-button>
              </div>
            </div>
          </el-card>
        </el-col>
        <el-col :span="8" style="padding: 20px; text-align: left">
          <div  v-if="!is_add"  @click="create" >
            <el-icon class="el-icon-circle-plus-outline"
                        style="color: #2CCB93; font-size: 50px; margin-top: 20px; cursor: pointer"></el-icon>
          </div>

          <el-card v-else shadow="always">
            <el-input placeholder="Nhập tên dự án" v-model="name" style="margin-bottom: 20px"></el-input>
            <el-input
                style="margin-bottom: 20px"
                type="textarea"
                :rows="2"
                placeholder="Nhập mô tả dự án"
                v-model="description">
            </el-input>
            <div class="users">
              <div class="label">
                <p><el-icon class="el-icon-user"></el-icon> Thành viên dự án</p>
              </div>
              <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">Chọn tất cả</el-checkbox>
              <div style="margin: 15px 0;"></div>
              <el-checkbox-group v-model="checkedUser" @change="handleCheckedCitiesChange">
                <el-checkbox v-for="user in users" :label="user.id" :key="user.id">{{user.name}}</el-checkbox>
              </el-checkbox-group>
              <div class="buton" style="text-align: right">
                <el-button @click="addProject" type="primary" plain>Thêm mới</el-button>
              </div>
            </div>
          </el-card>
        </el-col>
      </el-row>
      <el-dialog
          v-if="projectAnalytic.users"
      width="70%"
      :visible.sync="is_open_modal_analytic"
      >
        <div class="analytic" style="padding: 20px">
          <h2 style="margin-bottom: 30px">Thống kê công việc dự án {{projectAnalytic.name}}</h2>
          <el-row :gutter="12">
            <el-col :span="8">
              <el-card shadow="always">
                <div class="detaill">
                  <el-icon class="el-icon-s-claim"></el-icon>
                  <h3>Số nhiệm vụ: {{cardsByProject.length}}</h3>
                </div>
              </el-card>
            </el-col>
            <el-col :span="8">
              <el-card shadow="always">
                <div class="detaill">
                  <el-icon class="el-icon-user-solid"></el-icon>
                  <h3>Số thành viên: {{projectAnalytic.users.length}}</h3>
                </div>
              </el-card>
            </el-col>
          </el-row>
          <el-row>
            <el-col span="16">
              <div style="margin-top: 30px">
                <canvas id="planet-chart"  width="500" height="200"></canvas>
              </div>
            </el-col>
            <el-col span="8">
              <h4>Thành viên tham gia:</h4>
              <ul>
                <li v-for="(user) in projectAnalytic.users" :key="user.id" style="list-style: none; text-align: left">
                  <div class="u" style="display: flex; padding: 5px">
                    <img v-if="user.avatar && user.avatar.length > 0" :src="'http://127.0.0.1:8000/storage/users/'+ user.avatar" alt=""
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%">
                    <img v-else src="https://image.shutterstock.com/image-vector/default-avatar-profile-icon-grey-260nw-518740753.jpg" alt=""
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%">
                    <span style="margin-top: 13px; margin-left: 10px;" >{{ user.name }}</span>
                  </div>
                </li>
              </ul>
            </el-col>
          </el-row>
        </div>
      </el-dialog>
    </template>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/layouts/AdminLayout";
import api from '../../api'
import {mapState} from "vuex";
import Chart from 'chart.js'
import _ from "lodash";

export default {
  name: "Project",
  data(){
    return {
      is_open_modal_analytic: false,
      is_add:false,
      name: '',
      description: '',
      users:[],
      isIndeterminate: false,
      checkAll: false,
      checkedUser:[],
      projects:[],
      cardsByProject: [],
      projectAnalytic: {}
    }
  },
  components: {
    AdminLayout,
  },
  mounted() {
    this.getAllUser()
    this.getMyProject()
  },
  methods:{
    analytic(project, e){
      e.stopPropagation()
      this.projectAnalytic = project
      api.getCardByProject(project.id).then((res)=>{
        this.cardsByProject = res.data.data
        new Chart(document.getElementById('planet-chart'),{
          type: 'pie',
          data: {
            labels: ['Đã hoàn thành', 'Chưa hoàn thành', 'Trễ hạn'],
            datasets: [
              {
                label: '2018 Sales',
                borderWidth: 1,
                borderColor: [
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(255,99,132,1)',
                ],
                backgroundColor: [
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                ],
                data: [
                  _.filter(this.cardsByProject, function(item){
                    return item.status === 3
                  }).length,
                  _.filter(this.cardsByProject, function(item){
                    return item.status !== 3 && item.status !== 1
                  }).length,
                  _.filter(this.cardsByProject, function(item){
                    return item.status === 1
                  }).length,

                ]
              }
            ]
          }
        });
      })
      this.is_open_modal_analytic = true
    },
    destroy(id, e){
      e.stopPropagation()
      this.$confirm('Dữ liệu sau khi xóa sẽ không thể khôi phục?', 'Cảnh báo', {
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Đóng',
        type: 'warning'
      }).then(() => {
        api.removeProject(id).then((res)=>{
          this.$message({
            type: 'success',
            message: res.data.message
          });
        });
        this.getMyProject()
      })
    },
    detailProject(id){
      console.log(id)
      this.$router.push({
        name: 'Admin',
        params: {
          projectId: id
        }
      })
    },
    getMyProject(){
      api.getMyProject().then((res)=>{
        this.projects = res.data.data
      })
    },
    addProject(){
      let data = {
        name: this.name,
        description : this.description,
        usersId : this.checkedUser
      }
      api.storeProject(data).then((res)=>{
        this.$message(res.data.message)
        this.is_add=false
        this.name= ''
        this.description= ''
        this.isIndeterminate= false
        this.checkAll= false
        this.checkedUser=[]
        this.getMyProject()
      })
    },
    handleCheckAllChange(val) {
      let arr = []
      this.users.forEach(function (user){
        arr.push(user.id)
      })
      this.checkedUser = val ? arr : [];
      this.isIndeterminate = false;
    },
    handleCheckedCitiesChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.users.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.users.length;
    },
    create(){
      this.is_add = true
    },
    getAllUser(){
      api.allUser().then((res)=>{
        this.users = res.data.data
      })
    }
  },
  computed: {
    ...mapState('auth', [
      'authUser'
    ])
  },

}
</script>

<style scoped lang="scss">
.detaill{
  position: relative;
  i{
    position: absolute;
    left: 0;
    color: #80808045;
    font-size: 70px;
    top: -20px;
  }
}
</style>