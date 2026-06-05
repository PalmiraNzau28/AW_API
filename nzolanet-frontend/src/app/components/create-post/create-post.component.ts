import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, X, ImageIcon, Video } from 'lucide-angular';
import { PublicacaoService } from '../../services/publicacao.service';

@Component({
  selector: 'app-create-post',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule],
  templateUrl: './create-post.component.html',
  styleUrl: './create-post.component.scss'
})
export class CreatePostComponent {
  @Output() closeModal = new EventEmitter<void>();
  @Output() publicacaoCriada = new EventEmitter<void>();

  readonly XIcon = X;
  readonly ImageIcon = ImageIcon;
  readonly VideoIcon = Video;

  selectedPreview: string | null = null;
  selectedFile: File | null = null;
  selectedMediaType: 'image' | 'video' | null = null;
  texto = '';
  enviando = false;
  erro: string | null = null;
  private readonly maxImageBytes = 5 * 1024 * 1024;
  private readonly maxVideoBytes = 8 * 1024 * 1024;

  constructor(private publicacaoService: PublicacaoService) {}

  handleMediaSelect(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
      const isVideo = file.type.startsWith('video/');
      const maxBytes = isVideo ? this.maxVideoBytes : this.maxImageBytes;
      const maxLabel = isVideo ? '8MB' : '5MB';

      if (file.size > maxBytes) {
        this.selectedFile = null;
        this.selectedPreview = null;
        this.selectedMediaType = null;
        this.erro = `O ficheiro não pode ter mais de ${maxLabel}.`;
        return;
      }

      this.erro = null;
      this.selectedFile = file;
      this.selectedMediaType = isVideo ? 'video' : 'image';
      const reader = new FileReader();
      reader.onloadend = () => { this.selectedPreview = reader.result as string; };
      reader.readAsDataURL(file);
    }
  }

  handleSubmit(): void {
    if (!this.selectedFile && !this.texto.trim()) {
      this.erro = 'A publicação deve ter texto, imagem ou vídeo.';
      return;
    }

    const formData = new FormData();
    if (this.selectedFile && this.selectedMediaType === 'image') {
      formData.append('imagem', this.selectedFile);
    }
    if (this.selectedFile && this.selectedMediaType === 'video') {
      formData.append('video', this.selectedFile);
    }
    if (this.texto.trim()) {
      formData.append('texto', this.texto);
    }

    this.enviando = true;
    this.erro = null;

    this.publicacaoService.criar(formData).subscribe({
      next: () => {
        this.enviando = false;
        this.publicacaoCriada.emit();
        this.closeModal.emit();
      },
      error: err => {
        this.enviando = false;
        this.erro = err.error?.message ?? 'Erro ao criar publicação.';
        console.error(err);
      }
    });
  }
}
