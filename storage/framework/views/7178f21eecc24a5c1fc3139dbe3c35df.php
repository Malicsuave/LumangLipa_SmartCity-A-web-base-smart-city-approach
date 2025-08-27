<?php $__env->startSection('title', 'Contact - Barangay Lumanglipa'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">Contact Us</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Get in Touch</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong><i class="fe fe-map-pin me-2"></i>Address:</strong><br>
                                Barangay Hall, Lumanglipa<br>
                                [City/Municipality], [Province]
                            </div>
                            <div class="mb-3">
                                <strong><i class="fe fe-phone me-2"></i>Phone:</strong><br>
                                [Phone Number]
                            </div>
                            <div class="mb-3">
                                <strong><i class="fe fe-mail me-2"></i>Email:</strong><br>
                                [Email Address]
                            </div>
                            <div class="mb-3">
                                <strong><i class="fe fe-clock me-2"></i>Office Hours:</strong><br>
                                Monday - Friday: 8:00 AM - 5:00 PM<br>
                                Saturday: 8:00 AM - 12:00 PM
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Send us a Message</h4>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Emergency Contacts</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <i class="fe fe-phone fe-24 text-danger"></i>
                                    <h6>Police</h6>
                                    <p>117</p>
                                </div>
                                <div class="col-md-3">
                                    <i class="fe fe-phone fe-24 text-danger"></i>
                                    <h6>Fire Department</h6>
                                    <p>116</p>
                                </div>
                                <div class="col-md-3">
                                    <i class="fe fe-phone fe-24 text-danger"></i>
                                    <h6>Medical Emergency</h6>
                                    <p>911</p>
                                </div>
                                <div class="col-md-3">
                                    <i class="fe fe-phone fe-24 text-primary"></i>
                                    <h6>Barangay Emergency</h6>
                                    <p>[Emergency Number]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/public/contact.blade.php ENDPATH**/ ?>