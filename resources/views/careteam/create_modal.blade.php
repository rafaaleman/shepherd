<div class="modal fade" tabindex="-1" role="dialog" id="createMemberModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>@{{action}}</span> MEMBER</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            
            <form method="post" id="createMemberForm" class="col-md-12 p-3" v-on:submit.prevent="saveNewMember()" enctype="multipart/form-data">

                <div class="section photo text-center">
                    <img src="{{ asset('public/img/avatar2.png')}}" alt="" class="photo">
                    <input type="file" name="" id="photo" class="d-none" v-on:change="onFileChange" accept=".jpg, .png">
                </div>

                <div class="section">
                    <table>
                        <tr>
                            <td>Role:</td>
                            <td>
                                <select name="role" id="role" v-model="member.role_id" class="form-control" :disabled="(member.role_id == 'admin')">
                                    @foreach ($roles as $key => $role)
                                        <option value="{{$key}}" {{ ($key == 'admin') ? 'disabled' : ''}}>{{$role}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Relationship:</label></td>
                            <td>
                                <select name="relationship" id="relationship" v-model="member.relationship_id" class="form-control">
                                    @foreach ($relationships as $relationship)
                                        <option value="{{$relationship->id}}">{{$relationship->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <table>
                        <tr>
                            <td><label for="">First name:</label></td>
                            <td><input type="text" name="name" id="name" class="form-control" required v-model="member.name" ></td>
                        </tr>
                        <tr>
                            <td><label for="">Last name:</label></td>
                            <td><input type="text" name="lastname" id="lastname" class="form-control" required v-model="member.lastname" ></td>
                        </tr>
                        <tr>
                            <td><label for="">Email:</label></td>
                            <td><input type="email" name="email" id="email" class="form-control" required v-model="member.email" ></td>
                        </tr>
                        <tr class="password-field">
                            <td><label for="">Password:</label></td>
                            <td><input type="text" name="password" id="password" class="form-control" v-model="member.password" ></td>
                        </tr>
                        <tr>
                            <td><label for="">Phone:</label></td>
                            <td><input type="tel" name="phone" id="phone" class="form-control" required v-model="member.phone" ></td>
                        </tr>
                        <tr>
                            <td><label for="">Address:</label></td>
                            <td><input type="text" name="address" id="address" class="form-control"  v-model="member.address"></td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="id" id="id" required v-model="member.id">
                <button class="btn btn-primary loadingBtn btn-lg my-2" type="submit" data-loading-text="Saving..." id="saveBtn">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>

@push('css')
@endpush



@push('scripts')
<script>
$(function(){
    $('#createMemberModal img.photo').click(function(){
        console.log('click');
        $('#createMemberModal #photo').click();
    });
});
</script>
@endpush
