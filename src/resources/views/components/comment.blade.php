@props(['comment', 'profile'])

<div class="product-detail__comment">
    <div class="product-detail__comment-box1">
        <img class="product-detail__comment-avatar" src="{{ $profile && $profile->image_path ? asset('storage/' . $profile->image_path) : '' }}" alt="プロフィール画像">

        <p class="product-detail__comment-username">{{ $comment->user->name }}</p>
    </div>

    <div class="product-detail__comment-box2">
        <p class="product-detail__comment-text">{{ $comment->content }}</p>
    </div>
</div>