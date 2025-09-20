# 📖 Book Recommendation System  

## 🔹 Overview  
The **Book Recommendation System** is a PHP and MariaDB-based web application that helps users discover and access books based on **titles, authors, and genres**.  
It provides both **free and premium access** to books, with features like preview, subscription, and PDF download.  

---

## 🔹 Features  
- 👤 **User Authentication** – Registration & Login system  
- 📚 **Book Management (CRUD)** – Admin can add, edit, update, and delete books  
- 🏷️ **Genre Management** – Books categorized into multiple genres (many-to-many relationship)  
- 🔎 **Search & Recommendation** – Content-based filtering by title, author, and genre  
- 🖼️ **Book Images & Details** – Attractive UI for browsing  
- 📄 **PDF Support** – Users can  download books free books and purchase premium book  
- 💳 **Premium Access** – Books with `access_type = 1` are premium  
  - Preview 2–3 pages  
  - Redirects to **Stripe Payment** for purchase  
  - Full book available after successful payment (valid for 36 hours)  

---

## 🔹 Tech Stack  
- **Frontend:** HTML, CSS, Bootstrap  
- **Backend:** PHP  
- **Database:** MariaDB (via phpMyAdmin)  
- **Payment Integration:** Stripe  

---

## 🔹 Installation  
1. Clone the repository:  
   ```bash
   git clone https://github.com/your-username/book-recommendation-system.git
