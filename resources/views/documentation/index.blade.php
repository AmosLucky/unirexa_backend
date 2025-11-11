<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Unirexa API Documentation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 900px;
      margin: 2rem auto;
      padding: 0 1rem;
      background: #f9f9f9;
      color: #333;
    }
    h1 {
      text-align: center;
      margin-bottom: 2rem;
    }
    section {
      background: #fff;
      border-radius: 8px;
      padding: 1.5rem 2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h2 {
      border-bottom: 2px solid #4CAF50;
      padding-bottom: 0.3rem;
      margin-bottom: 1rem;
      color: #4CAF50;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 0.7rem 1rem;
      text-align: left;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    code {
      background-color: #eee;
      padding: 0.2rem 0.4rem;
      border-radius: 3px;
      font-size: 0.95rem;
    }
    .note {
      font-style: italic;
      color: #666;
    }
  </style>
</head>
<body>
  <h1>Unirexa API Documentation</h1>

  <section>
    <h2>1. User Registration</h2>
    <p><strong>POST</strong> <code>/register</code></p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>phone</td><td>string</td><td>User phone number (required)</td></tr>
        <tr><td>email</td><td>string</td><td>User email (optional)</td></tr>
        <tr><td>password</td><td>string</td><td>Password (required)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with user details and token on success, or errors array on failure.</p>
  </section>

  <section>
    <h2>2. User Login</h2>
    <p><strong>POST</strong> <code>/login</code></p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>phone</td><td>string</td><td>User phone number (required)</td></tr>
        <tr><td>password</td><td>string</td><td>Password (required)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with user details and token on success, or errors array on failure.</p>
  </section>

  <section>
    <h2>3. Set Username</h2>
    <p><strong>POST</strong> <code>/user/set-username</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>username</td><td>string</td><td>Unique username, alphanumeric only (required)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with updated user info or error if username exists/invalid.</p>
  </section>

  <section>
    <h2>4. Get User Profile</h2>
    <p><strong>GET</strong> <code>/user/profile</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p><strong>Response:</strong> JSON with full user details (including username, bio, avatar URL etc.)</p>
  </section>

  <section>
    <h2>5. Update User Profile</h2>
    <p><strong>POST</strong> <code>/user/update</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p>Send any combination of key-value pairs to update user fields (e.g., name, bio, phone).</p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>name</td><td>string</td><td>Optional</td></tr>
        <tr><td>bio</td><td>string</td><td>Optional</td></tr>
        <tr><td>phone</td><td>string</td><td>Optional</td></tr>
        <tr><td>any other user field</td><td>string</td><td>Optional</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with updated user data or errors.</p>
  </section>

  <section>
    <h2>6. Upload User Avatar</h2>
    <p><strong>POST</strong> <code>/user/avatar</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p>Send multipart/form-data with file field <code>avatar</code>.</p>
    <p><strong>Response:</strong> JSON with updated avatar URL (prefixed with domain).</p>
  </section>

  <section>
    <h2>7. Create Post</h2>
    <p><strong>POST</strong> <code>/posts</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p>Fields:</p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>content</td><td>string</td><td>Text content (optional if media provided)</td></tr>
        <tr><td>media</td><td>array of files</td><td>Images/videos (optional if content provided)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with post data or validation errors.</p>
  </section>

  <section>
    <h2>8. Get All Posts</h2>
    <p><strong>GET</strong> <code>/posts</code></p>
    <p><strong>Response:</strong> JSON list of posts with likes, shares, comments count, and media URLs.</p>
  </section>

  <section>
    <h2>9. Get User Posts</h2>
    <p><strong>GET</strong> <code>/user/{id}/posts</code></p>
    <p><strong>Response:</strong> JSON list of posts for the given user ID.</p>
  </section>

  <section>
    <h2>10. Get Post Comments</h2>
    <p><strong>GET</strong> <code>/posts/{id}/comments</code></p>
    <p><strong>Response:</strong> JSON list of comments on the post.</p>
  </section>

  <section>
    <h2>11. Create Comment</h2>
    <p><strong>POST</strong> <code>/comments</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p>Fields:</p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>commentable_type</td><td>string</td><td>Model name, e.g., <code>post</code> or <code>listing</code> (required)</td></tr>
        <tr><td>commentable_id</td><td>integer</td><td>ID of post or listing (required)</td></tr>
        <tr><td>comment</td><td>string</td><td>Comment text (required)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with created comment or errors.</p>
  </section>

  <section>
    <h2>12. Like / Unlike Post or Listing</h2>
    <p><strong>POST</strong> <code>/like</code></p>
    <p><em>Requires Authorization Bearer Token</em></p>
    <p>Fields:</p>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Type</th><th>Description</th></tr>
      </thead>
      <tbody>
        <tr><td>likeable_type</td><td>string</td><td><code>post</code> or <code>listing</code> (required)</td></tr>
        <tr><td>likeable_id</td><td>integer</td><td>ID of post or listing (required)</td></tr>
      </tbody>
    </table>
    <p><strong>Response:</strong> JSON with current like status.</p>
  </section>

  <section>
    <h2>13. Share Post or Listing</h2>
    <p><strong>POST</strong> <code>/share</
