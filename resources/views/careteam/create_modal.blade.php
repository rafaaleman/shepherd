<div class="modal fade" tabindex="-1" role="dialog" id="createMemberModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>@{{action}}</span> MEMBER</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4 row">
            
            <form method="post" id="createMemberForm" action="" class="col-md-12" v-on:submit.prevent="saveNewMember()">

                <label for="">Role:</label>
                <select name="role" id="role" v-model="member.role_id" class="form-control mb-3" :disabled="(member.role_id == 'admin')">
                    @foreach ($roles as $key => $role)
                        <option value="{{$key}}" {{ ($key == 'admin') ? 'disabled' : ''}}>{{$role}}</option>
                    @endforeach
                </select>
                
                <label for="">First name:</label>
                <input type="text" name="name" id="name" class="form-control mb-3" required v-model="member.name" >

                <label for="">Last name:</label>
                <input type="text" name="lastname" id="lastname" class="form-control mb-3" required v-model="member.lastname" >

                <label for="">Relationship:</label>
                <select name="relationship" id="relationship" v-model="member.relationship_id" class="form-control mb-3">
                    @foreach ($relationships as $relationship)
                        <option value="{{$relationship->id}}">{{$relationship->name}}</option>
                    @endforeach
                </select>

                <label for="">Email:</label>
                <input type="email" name="email" id="email" class="form-control mb-3" required v-model="member.email" >

                <div class="password-field">
                    <label for="">Password:</label>
                    <input type="text" name="password" id="password" class="form-control mb-3" v-model="member.password" >
                </div>

                <label for="">Phone:</label>
                <input type="tel" name="phone" id="phone" class="form-control mb-3" required v-model="member.phone" >

                <label for="">Address:</label>
                <input type="text" name="address" id="address" class="form-control mb-3"  v-model="member.address">


                <input type="hidden" name="id" id="id" required v-model="member.id">
                <button class="btn btn-primary loadingBtn btn-lg mt-2" type="submit" data-loading-text="Saving..." id="saveBtn">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>

@push('css')
@endpush



@push('scripts')
<script>

</script>
@endpush
