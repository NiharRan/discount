
 <div class="container-fluid" id="tag">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <h3 class="mb-0"><i class="fas fa-tag"></i> Tag Lists</h3>
                        </div>
                        <div class="form-group">
                            <input type="text" @keyup="fetchMenuTags" v-model="search" class="form-control" placeholder="Search">
                        </div>
                        <div class="form-group mx-sm-3">
                            <!-- Button trigger tag create modal -->
                            <?php if($this->permission->has_permission('menu-tag', 'create')) {?>
                                <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#tagCreateModal')" data-toggle="modal">
                                    <i class="fas fa-plus"></i> New
                                </button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- if menu_tags is not empty -->
                    <div class="row" v-if="menu_tags.length > 0">
                        <!-- show all menu_tags -->
                        <div class="flex-container">
                            <div class="flex-item" v-for="menu_tag in menu_tags" :key="menu_tag.menu_tag_id">
                                <p class="custom-btn" :class="[ menu_tag.menu_tag_status == 1 ? 'btn-outline-success' : 'btn-outline-warning' ]">
                                    {{ menu_tag.menu_tag_name }}
                                    <span class="icon-list">
                                        <i class="fas fa-edit text-info" @click="edit(menu_tag)"></i>
                                        <i class="fas fa-trash text-danger" @click="remove(menu_tag.menu_tag_id)"></i>
                                        <i class="fas fa-eye text-warning" @click="changeStatus(menu_tag)"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <!-- pagination links -->
                    <nav aria-label="..." v-html="links"></nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Tag Create Modal -->
    <div class="modal fade" id="tagCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="tagCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="tagCreateModalLabel">Create New Food Tags</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="store" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body">
                        <multiselect 
                            v-model="formData.menu_tags" 
                            :height="300"
                            :options="options" 
                            :multiple="true" 
                            :taggable="true"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :preserve-search="true" 
                            tag-placeholder="Add this as new tag" 
                            placeholder="Search or add a tag"
                            @tag="addTag"
                            label="menu_tag_name" 
                            track-by="menu_tag_name" 
                            :preselect-first="false"
                        >
                        </multiselect>
                        <!-- if tag field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.menu_tag_name }}</span>
                    </div>
                    <div class="modal-footer">
                        <!-- close to modal by clicking this button -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- this if for submit form -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tag Edit Modal -->
    <div class="modal fade" id="tagEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="tagEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content modal-xs">
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <input type="text" v-model="formData.menu_tag_name" class="form-control">
                        <input type="hidden" v-model="formData.menu_tag_id">
                        <!-- if tag field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.menu_tag_name }}</span>
                    </div>
                    <div class="modal-footer">
                        <!-- close to modal by clicking this button -->
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <!-- this if for submit form -->
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>