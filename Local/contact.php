<?php include("header.php"); ?>
<div class="main-content">
<div class="container mt-5">
  <h2>Contact Us</h2>
  <p>If you have any questions, feedback, or concerns, we’d love to hear from you.</p>

  <form method="POST" action="contactprocess.php">
    <div class="mb-3">
      <label for="name" class="form-label">Your Name</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Your Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn btn-dark">Send Message</button>
  </form>
</div>
	</div>
<?php include("footer.php"); ?>
