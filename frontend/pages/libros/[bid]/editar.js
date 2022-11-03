import Head from "next/head";
import Link from "next/link";
import { useRouter } from "next/router";
import { useState } from "react";
import styles from "../../../styles/Home.module.css";

export async function getServerSideProps({ params }) {
  const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/books/${params.bid}`);
  const data = await res.json();

  return {
    props: {
      book: data
    }
  }
}

const BookEdit = ({ book }) => {
  const router = useRouter();
  const [bookTitle, setBookTitle] = useState(book.title);
  const [errors, setErrors] = useState([]);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e) {
    e.preventDefault();
    setSubmitting(true);

    const res = await fetch(
      `${process.env.NEXT_PUBLIC_BACKEND_URL}/api/books/${book.id}`,
      {
        method: "POST",
        headers: {
          accept: "application/json",
          "content-type": "application/json",
        },
        body: JSON.stringify({
          title: bookTitle,
          _method: 'PATCH'
        }),
      }
    );
    if (res.ok) {
      setErrors([]);
      setBookTitle("");
      return router.push("/libros");
    }
    const data = await res.json();
    setErrors(data.errors);
    setSubmitting(false);
  }
  return (
    <div className={styles.container}>
      <h1>Editar Libro {book.id }</h1>

      <form onSubmit={handleSubmit}>
        <input
          onChange={(e) => setBookTitle(e.target.value)}
          value={String(bookTitle)}
          disabled={submitting}
          type="text"
        />
        <button disabled={submitting}>
          {submitting ? "Enviando..." : "Enviar"}
        </button>
        {errors.title && (
          <span style={{ color: "red", display: "block" }}>{errors.title}</span>
        )}
      </form>
      <br />

      <Link href="/libros">Lista de Libros</Link>
    </div>
  );
};

export default BookEdit;
