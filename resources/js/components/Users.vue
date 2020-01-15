<template>

            <div class="col-12">
                           <div class="card" v-if="$gate.isAdmin()" >
              <div class="card-header">
                <h3 class="card-title">User List</h3>
                <div class="input-group input-group-sm float-right" style="width: 150px;">
                  <button type="button" class="btn btn-block btn-success" @click="showModal"> <i class="fa fa-plus" aria-hidden="true"></i> Add user</button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th style="width: 40px">Type</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr  v-for="(user, index) in users" :key="index">
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>   {{user.email}}    </td>
                      <td><span class="badge bg-success">{{user.type | capitalize}}</span></td>
                       <td><span class="badge bg-primary">{{user.created_at | created}}</span></td>
                      <td>
                        <button type="button" class="btn  btn-danger btn-sm" @click="deleteUser(user.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                         <button type="button" class="btn btn-primary btn-sm" @click="editUser(user)"><i class="fas fa-edit    "></i></button>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">Add USer</h5>
         <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Edit USer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form @submit.prevent="editmode?updateUser():createUser()" @keydown="form.onKeydown($event)">

            <div class="form-group">
            <label>Name</label>
            <input v-model="form.name" type="text" name="name"
                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
            <has-error :form="form" field="name"></has-error>
            </div>

             <div class="form-group">
                <label>Email</label>
                <input v-model="form.email" type="email" name="email"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                <has-error :form="form" field="email"></has-error>
            </div>

            <div class="form-group">
                <label>Biography</label>
                <textarea v-model="form.bio" name="bio"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                <has-error :form="form" field="bio"></has-error>
            </div>

            <div class="form-group">
                <label for="sel1">Select Type:</label>
                <select class="form-control" id="sel1" name="type" v-model="form.type" :class="{ 'is-invalid': form.errors.has('type') }">
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                    <option value="Author">Author</option>
                    <option value="4">4</option>
                </select>
                  <has-error :form="form" field="type"></has-error>
                </div>



            <div class="form-group">
            <label>Password</label>
            <input v-model="form.password" type="password" name="password"
                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
            <has-error :form="form" field="password"></has-error>
            </div>
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" v-show="!editmode" class="btn btn-primary">Save changes</button>
        <button type="submit" v-show="editmode" class="btn btn-success">Update</button>

        </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
            </div>

</template>

<script>
import Form from 'vform';
    export default {
         data (){

             return{
                 users:{},
                 editmode:false,
                form: new Form({
                    id:'',
                    name:'',
                    email:'',
                    bio:'',
                    password:'',
                    type:''
                })
             }

         },
         methods:{
             showModal(){
                this.editmode=false;
                this.form.reset();
                $('#addModal').modal('show');
             },
             updateUser(){
                 this.form.patch('api/users/'+this.form.id).then(()=>{
                     Fire.$emit('myEvent')

                 })
             },
             editUser($user){
                 this.editmode=true;
                 this.form.reset();
                 this.form.clear ();
                  $('#addModal').modal('show');
                  this.form.fill($user);
             },
             deleteUser($id){
                 Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {

                    axios.delete('api/users/'+$id).then((data)=>{

                        console.log(data);
                        Fire.$emit('myEvent')
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )

                    });


                }
                })
             },
             loadUsers(){

                 axios.get("api/users").then(({data})=>(this.users =data.data ))

             },
             createUser(){

                  this.form.post('api/users')
                    .then(({ data }) => {
                        $('#addModal').modal('hide');
                        Fire.$emit('myEvent')
                        //this.loadUsers();
                        Toast.fire({
                        icon: 'success',
                        title: 'Signed in successfully'
                        })
                        })
             }
         },
        created() {
            this.loadUsers();
            Fire.$on('myEvent',()=>{
                 this.loadUsers();
            });
        },
        mounted(){

        }
    }
</script>
