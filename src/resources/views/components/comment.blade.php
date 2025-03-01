<div class="product-detail__comment">
    <div class="product-detail__comment-box1">
        <div class="product-detail__comment-avatar">
            <img src="{{ $profileImagePath }}" alt="{{ $profileImagePath ? 'プロフィール画像' : '' }}">
        </div>

        <p class="product-detail__comment-username">{{ $username }}</p>
    </div>

    <div class="product-detail__comment-box2">
        <p class="product-detail__comment-text">{{ $comment->content }}</p>
    </div>
</div>