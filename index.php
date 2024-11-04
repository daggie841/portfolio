<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
           
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
           
            display: flex;
            align-items: flex-start; /* Align items to the top */
            gap: 20px;
            margin-bottom: 20px;
        }

        .wrapper img, .wrapper video {
            background-color: hotpink;
            height: 50vh;
            width: 50%;
            object-fit: cover;
            border-radius: 10px;
        }

        .wrapper .info {
            border-radius: 10px;
            width: 50%;
            padding: 20px; /* Add padding to the info section */
            box-sizing: border-box; /* Ensure padding doesn't affect the width */
        }

        /* Alternate layouts */
        #food .wrapper {
            flex-direction: row;
        }

        #room .wrapper {
            flex-direction: row-reverse;
        }

        #events .wrapper {
            flex-direction: row;
        }

        #gallery .wrapper {
            flex-direction: row-reverse;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .logo-container {
            
            width: 100%;
            padding: 20px; /* Add padding to the logo container */
            box-sizing: border-box;
        }

        .logo-container img {
            width: 100%;
            height: auto;
            max-height: 70vh; /* 30% of the viewport height */
            object-fit: cover;
            border-radius: 10px;
        }

        .video-container {
            width: 100%;
            margin: 20px 0;
        }

        .video-container video {
            width: 100%;
            height: 500px;
            border-radius: 10px;
        }

        .gallery {
            background-color: hotpink;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
            padding: 10px;
        }

        .gallery-item {
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .gallery-item img {
            display: block;
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .modal-controls {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            z-index: 1100;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }

        .modal-controls .arrow {
            color: white;
            font-size: 40px;
            cursor: pointer;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .wrapper img, .wrapper .info, .wrapper video {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 20px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="img/bar.png" alt="Logo">
        <p>In X timber hotels we offer a culinary journey that celebrates the rich diversity of our cuisine. Among the most famous dishes enjoyed at these establishments are meticulously prepared <b>Nyama Choma, succulent grilled meats marinated in a blend of spices and served with traditional sides like Ugali, a hearty maize porridge. Pilau, a fragrant rice dish cooked with aromatic spices and often accompanied by tender chunks of beef or chicken, showcases the country's vibrant flavors.</b> Additionally, Samosas, crispy pastries filled with spiced meat or vegetables, provide a delightful appetizer or snack. These dishes, served with warmth and hospitality, embody Kenya's culinary heritage and make dining at its X TIMBER hotels an unforgettable experience.</p>
    </div>

    <div class="video-container">
        <video controls>
            <source src="img/restaurant.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <section id="food">
        <h2>FOODS IN X TIMBER HOTEL</h2>
        <div class="wrapper">
            <img src="img/fish.jpg" alt="Food Image">
            <div class="info">
                <p>In X timber hotels we offer a culinary journey that celebrates the rich diversity of our cuisine. Among the most famous dishes enjoyed at these establishments are meticulously prepared <b>Nyama Choma, succulent grilled meats marinated in a blend of spices and served with traditional sides like Ugali, a hearty maize porridge. Pilau, a fragrant rice dish cooked with aromatic spices and often accompanied by tender chunks of beef or chicken, showcases the country's vibrant flavors.</b> Additionally, Samosas, crispy pastries filled with spiced meat or vegetables, provide a delightful appetizer or snack. These dishes, served with warmth and hospitality, embody Kenya's culinary heritage and make dining at its X TIMBER hotels an unforgettable experience.<b><a href="foodlist.php">View Menu</a></b></p>
            </div>
        </div>
    </section>

    <section id="room">
        <h2>ROOMS IN X TIMBER HOTEL</h2>
        <div class="wrapper">
            <img src="img/room.png" alt="Room Image">
            <div class="info">
                <p>In X Timber Hotel, We offers a perfect blend of comfort and functionality in our rooms. Featuring a luxurious bed with a high-quality mattress and soft linens, it ensures a restful night. Thoughtful lighting and climate control systems cater to diverse needs, while a spacious wardrobe provides convenience. The immaculate bathroom offers modern amenities and complimentary toiletries, complemented by a subtle room fragrance. Guests enjoy fresh towels daily and a noise-free environment, ideal for relaxation or work. Overall, it's a serene retreat designed for both relaxation and productivity.<b><a href="room.php">View Rooms</a></b></p>
            </div>
        </div>
    </section>

    <section id="events">
        <h2>EVENTS & ENTERTAINMENT IN X TIMBER HOTEL</h2>
        <div class="wrapper">
            <img src="img/og.png" alt="Event Image">
            <div class="info">
                <p>In X Timber Hotel we hosts exciting events and entertainment every last Friday of the month, featuring renowned musicians from around the globe performing live in our restaurant. This initiative aims to enhance our guests' experience, providing memorable moments alongside exceptional dining. Additionally, on Valentine's Day, we celebrate with a special event where everyone is encouraged to wear red attire, creating a festive atmosphere. Guests enjoy complimentary drinks, adding to the romantic ambiance and making it an unforgettable evening at X Timber Hotel. These events are designed not only to entertain but also to foster a sense of community and celebration among our valued guests, ensuring each visit is filled with joy, relaxation, and cultural enrichment.<b><a href="eventlist.php">View Events</a></b></p>
            </div>
        </div>
    </section>

    <section id="gallery">
        <h2>GALLERY</h2>
        <div class="gallery">
            <div class="info">
                <p>Experience the elegance and comfort of our hotel with our beautiful gallery showcasing our lodging and bar. Immerse yourself in the stunning visuals of our luxurious rooms, cozy beds, and modern amenities. Discover the charm of our stylish bar, perfect for relaxing with a drink after a day of exploring. Our gallery captures the essence of our hotel, offering a glimpse into the exceptional hospitality and unforgettable experiences that await you.<b><a href="gallary.php">View Gallery</a></b></p>
            </div>
            <div class="gallery-item">
                <img src="img/bedroom.jpeg" alt="Bedroom" onclick="openModal('img/bedroom.jpeg')">
            </div>
            <div class="gallery-item">
                <img src="img/reroom.png" alt="Bedroom" onclick="openModal('img/reroom.png')">
            </div>
            <div class="gallery-item">
                <img src="img/rest.png" alt="Restaurant" onclick="openModal('img/rest.png')">
            </div>
        </div>
    </section>

    <script>
        let currentImageIndex = 0;
        let images = [];

        function openModal(imageUrl) {
            const modal = document.createElement('div');
            modal.classList.add('modal');

            images = Array.from(document.querySelectorAll('.gallery-item img')).map(img => img.src);
            currentImageIndex = images.indexOf(imageUrl);

            modal.innerHTML = `
                <span class="close" onclick="closeModal()">&times;</span>
                <img class="modal-content" src="${imageUrl}">
                <div class="modal-controls">
                    <span class="arrow left-arrow" onclick="changeImage(-1)">&#10094;</span>
                    <span class="arrow right-arrow" onclick="changeImage(1)">&#10095;</span>
                </div>
            `;
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function closeModal() {
            const modal = document.querySelector('.modal');
            if (modal) {
                modal.remove();
                document.body.style.overflow = ''; // Re-enable scrolling
            }
        }

        function changeImage(direction) {
            currentImageIndex += direction;
            if (currentImageIndex >= images.length) {
                currentImageIndex = 0;
            } else if (currentImageIndex < 0) {
                currentImageIndex = images.length - 1;
            }
            const modalContent = document.querySelector('.modal-content');
            if (modalContent) {
                modalContent.src = images[currentImageIndex];
            }
        }
    </script>

</body>
</html>
<?php include 'footer.php'; ?>
