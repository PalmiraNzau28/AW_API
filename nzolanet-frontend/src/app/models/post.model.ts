export interface Comment {
  id: string;
  username: string;
  text: string;
  avatar: string;
}

export interface PostUser {
  username: string;
  avatar: string;
  name: string;
}

export interface Post {
  id: string;
  user: PostUser;
  image: string;
  caption: string;
  likes: number;
  comments: Comment[];
  timestamp: string;
}
