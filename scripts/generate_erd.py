import matplotlib.pyplot as plt
from matplotlib.patches import FancyBboxPatch

TABLES = {
    "courses": {
        "columns": [
            "PK id",
            "course",
            "about",
            "image"
        ],
        "pos": (1.5, 8.5)
    },
    "alumnus_bio": {
        "columns": [
            "PK id",
            "firstname",
            "lastname",
            "batch",
            "FK course_id",
            "email",
            "contact",
            "status"
        ],
        "pos": (4.5, 8.5)
    },
    "users": {
        "columns": [
            "PK id",
            "name",
            "username",
            "password",
            "type",
            "FK alumnus_id"
        ],
        "pos": (7.5, 8.5)
    },
    "alumni_documents": {
        "columns": [
            "PK id",
            "FK alumnus_id",
            "document_type",
            "document_name",
            "file_path",
            "is_verified",
            "FK verified_by"
        ],
        "pos": (4.5, 6.5)
    },
    "announcements": {
        "columns": [
            "PK id",
            "title",
            "image",
            "content",
            "date_posted"
        ],
        "pos": (13.5, 8.5)
    },
    "careers": {
        "columns": [
            "PK id",
            "company",
            "location",
            "job_title",
            "user_id",
            "date_created"
        ],
        "pos": (10.5, 8.5)
    },
    "job_applications": {
        "columns": [
            "PK id",
            "FK job_id",
            "FK user_id",
            "cover_letter",
            "resume_file",
            "status",
            "applied_at"
        ],
        "pos": (10.5, 6.5)
    },
    "events": {
        "columns": [
            "PK id",
            "title",
            "content",
            "schedule",
            "participant_limit"
        ],
        "pos": (4.5, 4.5)
    },
    "event_participants": {
        "columns": [
            "PK id",
            "FK event_id",
            "FK user_id",
            "registration_date",
            "status"
        ],
        "pos": (7.5, 4.5)
    },
    "event_commits": {
        "columns": [
            "PK id",
            "FK event_id",
            "FK user_id"
        ],
        "pos": (1.5, 4.5)
    },
    "forum_topics": {
        "columns": [
            "PK id",
            "title",
            "description",
            "FK user_id",
            "date_created"
        ],
        "pos": (1.5, 2.5)
    },
    "forum_comments": {
        "columns": [
            "PK id",
            "FK topic_id",
            "FK user_id",
            "comment",
            "date_created"
        ],
        "pos": (4.5, 2.5)
    },
    "notifications": {
        "columns": [
            "PK id",
            "FK user_id",
            "title",
            "message",
            "type",
            "is_read"
        ],
        "pos": (7.5, 2.5)
    },
    "success_stories": {
        "columns": [
            "PK id",
            "FK user_id",
            "title",
            "content",
            "image",
            "status"
        ],
        "pos": (10.5, 2.5)
    },
    "testimonials": {
        "columns": [
            "PK id",
            "FK user_id",
            "author_name",
            "graduation_year",
            "course",
            "status"
        ],
        "pos": (13.5, 2.5)
    },
    "gallery": {
        "columns": [
            "PK id",
            "image_path",
            "about",
            "created"
        ],
        "pos": (13.5, 6.5)
    },
    "system_settings": {
        "columns": [
            "PK id",
            "name",
            "email",
            "contact",
            "cover_img"
        ],
        "pos": (13.5, 4.5)
    },
    "read_logs": {
        "columns": [
            "PK id",
            "FK user_id",
            "log_id",
            "created_at"
        ],
        "pos": (7.5, 0.8)
    },
    "user_logs": {
        "columns": [
            "PK id",
            "FK user_id",
            "action",
            "action_type",
            "module",
            "created_at"
        ],
        "pos": (10.5, 0.8)
    }
}

RELATIONSHIPS = [
    ("alumnus_bio", "courses", "course_id ➜ id"),
    ("users", "alumnus_bio", "alumnus_id ➜ id"),
    ("alumni_documents", "alumnus_bio", "alumnus_id ➜ id"),
    ("alumni_documents", "users", "verified_by ➜ id"),
    ("careers", "users", "user_id ➜ id"),
    ("job_applications", "careers", "job_id ➜ id"),
    ("job_applications", "users", "user_id ➜ id"),
    ("event_commits", "events", "event_id ➜ id"),
    ("event_commits", "users", "user_id ➜ id"),
    ("event_participants", "events", "event_id ➜ id"),
    ("event_participants", "users", "user_id ➜ id"),
    ("forum_topics", "users", "user_id ➜ id"),
    ("forum_comments", "forum_topics", "topic_id ➜ id"),
    ("forum_comments", "users", "user_id ➜ id"),
    ("notifications", "users", "user_id ➜ id"),
    ("read_logs", "users", "user_id ➜ id"),
    ("success_stories", "users", "user_id ➜ id"),
    ("testimonials", "users", "user_id ➜ id"),
    ("user_logs", "users", "user_id ➜ id")
]

FIGURE_PATH = "docs/erd/sccalumni_erd.png"


def draw_table(ax, name, columns, pos, width=2.7, line_height=0.32, header_height=0.42):
    total_height = header_height + len(columns) * line_height
    left = pos[0] - width / 2
    bottom = pos[1] - total_height / 2

    body = FancyBboxPatch(
        (left, bottom), width, total_height,
        boxstyle="round,pad=0.02", linewidth=1,
        edgecolor="#243447", facecolor="#fdfdfd"
    )
    ax.add_patch(body)

    header = FancyBboxPatch(
        (left, bottom + total_height - header_height), width, header_height,
        boxstyle="round,pad=0.02", linewidth=0,
        facecolor="#1f77b4"
    )
    ax.add_patch(header)

    ax.text(
        pos[0], bottom + total_height - header_height / 2,
        name.upper(), color="white", fontsize=8, ha="center", va="center", fontweight="bold"
    )

    for idx, column in enumerate(columns):
        y = bottom + total_height - header_height - (idx + 0.5) * line_height
        color = "#333333"
        if column.startswith("PK"):
            color = "#c0392b"
        elif column.startswith("FK"):
            color = "#16a085"
        ax.text(left + 0.1, y, column, fontsize=7, ha="left", va="center", color=color, family="monospace")

    return {
        "center": pos,
        "width": width,
        "height": total_height
    }


def connect_tables(ax, src_box, dst_box, label):
    sx, sy = src_box["center"]
    dx, dy = dst_box["center"]
    ax.annotate(
        "", xy=(dx, dy), xytext=(sx, sy),
        arrowprops=dict(arrowstyle="-|>", color="#555555", shrinkA=15, shrinkB=15, linewidth=1.2)
    )
    ax.text(
        (sx + dx) / 2, (sy + dy) / 2 + 0.15,
        label, fontsize=6, ha="center", va="bottom", color="#444444",
        bbox=dict(boxstyle="round,pad=0.2", facecolor="#ffffff", edgecolor="none", alpha=0.85)
    )


def main():
    fig, ax = plt.subplots(figsize=(18, 11))
    ax.set_xlim(0, 15)
    ax.set_ylim(0, 10)
    ax.axis('off')
    ax.set_facecolor("#f2f4f7")

    boxes = {}
    for table_name, meta in TABLES.items():
        boxes[table_name] = draw_table(ax, table_name, meta["columns"], meta["pos"])

    for src, dst, label in RELATIONSHIPS:
        if src in boxes and dst in boxes:
            connect_tables(ax, boxes[src], boxes[dst], label)

    ax.text(
        0.2, 0.3,
        "SCC Alumni Portal ERD (Normalized Core Entities)\n" \
        "Color legend: PK (red), FK (green). Relationships shown via mandatory FK arrows.",
        fontsize=7, ha="left", va="bottom", color="#333333"
    )

    plt.tight_layout()
    fig.savefig(FIGURE_PATH, dpi=300)
    plt.close(fig)


if __name__ == "__main__":
    main()
