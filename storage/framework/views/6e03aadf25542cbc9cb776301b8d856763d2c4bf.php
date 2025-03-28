

<?php $__env->startSection('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular); ?>

<?php $__env->startSection('css'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_header'); ?>
    <h1 class="page-title">
        <i class="<?php echo e($dataType->icon); ?>"></i>
        <?php echo e(__('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular); ?>

    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="<?php echo e((isset($dataTypeContent->id)) ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->id) : route('voyager.'.$dataType->slug.'.store')); ?>"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            <?php if(isset($dataTypeContent->id)): ?>
                <?php echo e(method_field("PUT")); ?>

            <?php endif; ?>
            <?php echo e(csrf_field()); ?>


            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                    
                        <?php if(count($errors) > 0): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name"><?php echo e(__('voyager::generic.name')); ?></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('voyager::generic.name')); ?>"
                                       value="<?php if(isset($dataTypeContent->name)): ?><?php echo e($dataTypeContent->name); ?><?php endif; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email"><?php echo e(__('voyager::generic.email')); ?></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo e(__('voyager::generic.email')); ?>"
                                       value="<?php if(isset($dataTypeContent->email)): ?><?php echo e($dataTypeContent->email); ?><?php endif; ?>">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="username" class="form-control" id="username" name="username" placeholder="Username"
                                       value="<?php if(isset($dataTypeContent->username)): ?><?php echo e($dataTypeContent->username); ?><?php endif; ?>">
                            </div>

                            <div class="form-group">
                                <label for="password"><?php echo e(__('voyager::generic.password')); ?></label>
                                <?php if(isset($dataTypeContent->password)): ?>
                                    <br>
                                    <small><?php echo e(__('voyager::profile.password_hint')); ?></small>
                                <?php endif; ?>
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                            </div>


                                <?php
                                    $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};

                                    $row     = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                    if(is_string($row->details)){
                                        $options = json_decode($row->details);
                                    } else {
                                        $options = $row->details;
                                    }
                                ?>

                                <div class="form-group">
                                    <label for="role_id">Primary Role</label>
                                    <?php $roles = TCG\Voyager\Models\Role::all(); ?>
                                    <select name="role_id" id="role_id" class="select2" placeholder="">
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php if($role->id == $dataTypeContent->role_id): ?> selected <?php endif; ?>><?php echo e($role->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="additional_roles"><?php echo e(__('voyager::profile.roles_additional')); ?></label>
                                    <?php
                                        $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        if(is_string($row->details)){
                                            $options = json_decode($row->details);
                                        } else {
                                            $options = $row->details;
                                        }
                                    ?>
                                    <?php echo $__env->make('voyager::formfields.relationship', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                <?php if(isset($dataTypeContent->avatar)): ?>
                                    <img src="<?php echo e(filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar )); ?>" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                <?php endif; ?>
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                <?php echo e(__('voyager::generic.save')); ?>

            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="<?php echo e(route('voyager.upload')); ?>" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            <?php echo e(csrf_field()); ?>

            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="<?php echo e($dataType->slug); ?>">
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FYP\cybersentinel\resources\views/vendor/voyager/users/edit-add.blade.php ENDPATH**/ ?>